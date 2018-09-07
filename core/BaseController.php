<?php 

namespace Core;

abstract class BaseController
{
	protected $data;
	protected $auth;
	protected $error;
	protected $success;
	protected $inputs;
	private $viewPath;
	private $layoutPath;
	private $pageTitle = null;
	
	public function __construct()
	{
		$this->data = new \stdClass;
		$this->auth = new Auth;

		if (Session::get('error')) {
			$this->error = Session::get('error');
			Session::destroy('error');
		}

		if (Session::get('success')) {
			$this->success = Session::get('success');
			Session::destroy('success');
		}

		if (Session::get('inputs')) {
			$this->inputs = Session::get('inputs');
			Session::destroy('inputs');
		}
	}

	protected function renderView($viewPath, $layoutPath = null)
	{
		$this->viewPath = $viewPath;
		$this->layoutPath = $layoutPath;
		if ($layoutPath) {
			return $this->layout();
		} else {
			return $this->content();
		}
	}

	protected function content()
	{
		if (file_exists(__DIR__ . "/../app/Views/{$this->viewPath}.phtml")) {
			require_once __DIR__ . "/../app/Views/{$this->viewPath}.phtml";
		} else {
			echo "Error: View path not found!";
		}
	}

	protected function layout()
	{
		if (file_exists(__DIR__ . "/../app/Views/{$this->layoutPath}.phtml")) {
			require_once __DIR__ . "/../app/Views/{$this->layoutPath}.phtml";

		} else {
			echo "Error: Layout path not found!";
		}
	}

	protected function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle;
	}

	protected function getPageTitle($separator = null)
	{
		if ($separator) {
			return $this->pageTitle. " " .$separator. " ";;
		} else {
			return $this->pageTitle;
		}
	}

	public function forbiden()
	{
		return Redirect::route('/login');
	}
}

?>