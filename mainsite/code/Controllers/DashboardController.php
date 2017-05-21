<?php use SaltedHerring\Debugger as Debugger;

class DashboardController extends Page_Controller {
	private static $url_handlers = array(
		''				        =>	'index',
        'action/$tab/$order_id'      =>  'index'
	);

	private static $allowed_actions = array(
		'index',
		'signout',
		'MemberProfileForm',
		'UpdatePasswordForm',
		'UpdateEmailForm',
		'YoGoldPurchaseForm',
		'MiniSubscribeForm'
	);

	public function index($request) {
		if (!Member::currentUser()) {
			return $this->redirect('/signin?BackURL=/member');
		}

		$tab = $request->param('tab');
        $page_data = array('tab' => $tab);

        if (!empty($request->param('order_id'))) {
            $page_data['Order'] = Order::get()->byID($request->param('order_id'));
        }

        $page_data = new ArrayData($page_data);

		if ($request->isAjax()) {
			switch ($tab) {

				case 'password':
					return $this->customise($page_data)->renderWith(array('UpdatePasswordForm'));
					break;

				case 'email-update':
					return $this->customise($page_data)->renderWith(array('UpdateEmailForm'));
					break;

				case 'address':
					return $this->customise($page_data)->renderWith(array('MyAddresses'));
					break;

				case 'yo-gold':
					return $this->customise($page_data)->renderWith(array('YoGoldPurchaseForm'));
					break;

				case 'orders':
                    if (!empty($request->param('order_id'))) {
                        return $this->customise($page_data)->renderWith(array('OrderReceipt'));
                    }
					return $this->customise($page_data)->renderWith(array('OrderHistory'));
					break;

				case 'favourites':
					return $this->customise($page_data)->renderWith(array('FavouritesList'));
					break;
				case 'watch':
					return $this->customise($page_data)->renderWith(array('MyWatch'));
					break;
				default:
					return $this->customise($page_data)->renderWith(array('MemberProfileForm'));
			}
		}

		return $this->customise($page_data)->renderWith(array('Dashboard', 'Page'));
	}

	public function getFavourites() {
		if ($member = Member::currentUser()) {
			return $member->Favourites();
		}

		return Favourite::get()->filter(array('Session' => session_id()));
	}

	public function getWatchlist() {
		if ($member = Member::currentUser()) {
			return $member->Watchlist();
		}

		return null;
	}

	public function getSubscribe() {
		if ($member = Member::currentUser()) {
			return $member->Subscribe;
		}

		return false;
	}

	public function SigninForm() {
		return new SigninForm($this, 'SigninForm');
	}

	public function signout() {
		if ($member = Member::currentUser()) {
			$member->logOut();
		}

		$this->redirect('/');
	}

	public function MemberProfileForm() {
		return new MemberProfileForm($this);
	}

	public function UpdatePasswordForm() {
		return new UpdatePasswordForm($this);
	}

	public function UpdateEmailForm() {
		return new UpdateEmailForm($this);
	}

	public function YoGoldPurchaseForm() {
		return new YoGoldPurchaseForm($this);
	}

	public function MiniSubscribeForm($watch_id) {
		return new MiniSubscribeForm($this, $watch_id);
	}

	public function getOrders() {
		return Order::get()->filter(array(
			'CustomerID'				=>	Member::currentUserID(),
			'Progress:GreaterThan'		=>	2
		));
	}

	public function getAddresses() {
		$member = Member::currentUser();

		return $member->Address();
	}

	public function Title() {
		return '会员中心';
	}
}
