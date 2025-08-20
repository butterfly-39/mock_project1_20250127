<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Order; // Added this import for Order model
use App\Models\Message; // Added this import for Message model

class ProfileController extends Controller
{
	public function mypage_view(Request $request)
	{
		$user = Auth::user();
		$tab = $request->query('tab', 'trading');

		$averageRating = $user->getAverageRating();
		$ratingCount = $user->getRatingCount();
		$ratings = $user->getRatings();

		$items = collect();
		$tradingItems = collect();

		if ($tab === 'sell') {
			$items = Item::where('user_id', $user->id)->get();
		} elseif ($tab === 'buy') {
			$items = Item::whereHas('orders', function($query) use ($user) {
				$query->where('user_id', $user->id);
			})->where('status', 'sold')->get();
		} elseif ($tab === 'trading') {
			$tradingItems = Item::where(function($query) use ($user) {
				$query->where('user_id', $user->id)
					->orWhereHas('orders', function($orderQuery) use ($user) {
						$orderQuery->where('user_id', $user->id);
					});
			})->where('status', 'trading')
			->with(['messages' => function($query) {
				$query->where('is_deleted', false)
					->orderBy('created_at', 'desc');
			}])
			->get()
			->sortByDesc(function($item) {
				$latestMessage = $item->messages->first();
				return $latestMessage ? $latestMessage->created_at : $item->created_at;
			});

			foreach ($tradingItems as $item) {
				$item->unreadCount = Message::where('item_id', $item->id)
					->where('user_id', '!=', $user->id)
					->where('is_read', false)
					->where('is_deleted', false)
					->count();
			}
		}

		$unreadMessageCount = Message::whereHas('item', function($query) use ($user) {
			$query->where(function($itemQuery) use ($user) {
				$itemQuery->where('user_id', $user->id)
					->where('status', 'trading');
			})->orWhereHas('orders', function($orderQuery) use ($user) {
				$orderQuery->where('user_id', $user->id);
			});
		})->where('user_id', '!=', $user->id)
		->where('is_read', false)
		->where('is_deleted', false)
		->count();

		return view('profiles.mypage', compact(
			'user',
			'tradingItems',
			'tab',
			'averageRating',
			'ratingCount',
			'ratings',
			'unreadMessageCount',
			'items'
		));
	}

	public function edit_view()
	{
		$user = Auth::user();
		return view('profiles.profile', compact('user'));
	}

	public function edit_update(ProfileRequest $request)
	{
		$user = Auth::user();

		$user->update([
			'name' => $request->name
		]);

		$profile = $request->only(['postal_code', 'address', 'building']);

		if ($request->hasFile('image')) {
			$image = $request->file('image');
			$path = $image->store('profile_images', 'public');
			$profile['image'] = $path;
		}

		$user->profile()->updateOrCreate(
			['user_id' => $user->id],
			$profile
		);

		return redirect('/');
	}

}

