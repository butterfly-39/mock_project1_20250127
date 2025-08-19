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
		$user = auth()->user();
		$tab = $request->query('tab', 'trading');
		
		// 評価情報を取得
		$averageRating = $user->getAverageRating();
		$ratingCount = $user->getRatingCount();
		$ratings = $user->getRatings();
		
		// 変数を初期化
		$items = collect();  // ← 空のコレクションで初期化
		$tradingItems = collect();  // ← 空のコレクションで初期化

		if ($tab === 'sell') {
			$items = Item::where('user_id', $user->id)
				->where('status', 'available')
				->get();
		} elseif ($tab === 'buy') {
			$items = Item::whereHas('orders', function($query) use ($user) {
				$query->where('user_id', $user->id);
			})->where('status', 'sold')->get();
		} elseif ($tab === 'trading') {
			$tradingItems = Item::where(function($query) use ($user) {
				$query->where('user_id', $user->id)  // 自分が出品した商品
					->orWhereHas('orders', function($orderQuery) use ($user) {
						$orderQuery->where('user_id', $user->id);  // 自分が購入した商品
					});
			})->where('status', 'trading')
			->with(['messages' => function($query) {
				$query->where('is_deleted', false)
					->orderBy('created_at', 'desc');
			}])
			->get()
			->sortByDesc(function($item) {
				// 最新メッセージの日時でソート
				$latestMessage = $item->messages->first();
				return $latestMessage ? $latestMessage->created_at : $item->created_at;
			});
			
			// 各商品の未読メッセージ件数を計算（既読処理前の状態）
			foreach ($tradingItems as $item) {
				$item->unreadCount = Message::where('item_id', $item->id)
					->where('user_id', '!=', $user->id)  // 相手からのメッセージ
					->where('is_read', false)
					->where('is_deleted', false)
					->count();
			}
		}
		
		// 未読メッセージ総件数を計算（既読処理前の状態）
		$unreadMessageCount = Message::whereHas('item', function($query) use ($user) {
			$query->where(function($itemQuery) use ($user) {
				$itemQuery->where('user_id', $user->id)  // 自分が出品
					->where('status', 'trading');
			})->orWhereHas('orders', function($orderQuery) use ($user) {
				$orderQuery->where('user_id', $user->id);  // 自分が購入
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
		// usersテーブルの更新
		$user->update([
			'name' => $request->name
		]);

		// profilesテーブル用のデータ
		$profile = $request->only(['postal_code', 'address', 'building']);

		if ($request->hasFile('image')) {
			$image = $request->file('image');
			$path = $image->store('profile_images', 'public');
			$profile['image'] = $path;
		}

		// プロフィール情報を更新または作成
		$user->profile()->updateOrCreate(
			['user_id' => $user->id],
			$profile
		);

		return redirect('/');
	}

}

