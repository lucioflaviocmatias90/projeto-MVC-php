<?php 

namespace App\Controllers;

use App\Models\Post;
use App\Models\Category;
use Core\Auth;
use Core\BaseController;
use Core\Redirect;
use Core\Validator;

class PostsController extends BaseController {

	private $post;

	public function __construct()
	{
		parent::__construct();
		$this->post = new Post;
	}

	public function index()
	{
		$this->setPageTitle('Posts');
		$this->data->posts = $this->post->All();
		return $this->renderView('posts/index', 'layout');
	}

	public function view($id)
	{
		$this->data->post = $this->post->find($id);
		$this->setPageTitle($this->data->post->title);
		return $this->renderView('posts/view', 'layout');
	}

	public function create()
	{
		$this->setPageTitle('Novo Post');
		$this->data->categories = Category::all();
		return $this->renderView('posts/create', 'layout');
	}

	public function store($request)
	{
		$data = [
			'user_id'=>Auth::id(),
			'title'=>$request->post->title,
			'content'=>$request->post->content
		];	

		try {
			$post = $this->post->create($data);
			if (isset($request->post->category_id)) {
				$post->category()->attach($request->post->category_id);
			}
			return Redirect::route('/posts', [
				'success'=>['Post criado com sucesso!']
			]);
		} catch (\Exception $e) {
			return Redirect::route('/posts', [
				'error'=>[$e->getMessage()]
			]);
		}	
	}

	public function edit($id)
	{
		$this->data->post = $this->post->find($id);
		$this->data->categories = Category::all();
		if (Auth::id() != $this->data->post->id) {
			return Redirect::route('/posts', [
				'error'=>['Você não pode editar post de outro autor!']
			]);
		}
		$this->setPageTitle('Editar Post');
		return $this->renderView('posts/edit', 'layout');
	}

	public function update($id, $request)
	{
		$data = [
			'title'=>$request->post->title,
			'content'=>$request->post->content
		];

		if (Validator::make($data, $this->post->rules())) {
			return Redirect::route("/post/{$id}/edit");
		}

		try {
			$post = $this->post->find($id);
			$post->update($data);
			if (isset($request->post->category_id)) {
				$post->category()->sync($request->post->category_id);
			} else {
				$post->category()->detach();
			}
			return Redirect::route('/posts', [
				'success'=>['Post atualizado com sucesso!']
			]);
		} catch (\Exception $e) {
			return Redirect::route('/posts', [
				'error'=>[$e->getMessage()]
			]);
		}
	}

	public function delete($id)
	{
		try {
			$post = $this->post->find($id);

			if (Auth::id() != $post->user->id) {
				return Redirect::route('/posts', [
					'error'=>['Você não pode excluir post de outro autor!']
				]);
			}

			$post->delete();

			return Redirect::route('/posts', [
				'success'=>['Post excluido com sucesso!']
			]);

		} catch (\Exception $e) {
			return Redirect::route('/posts', [
				'error'=>[$e->getMessage()]
			]);
		}
	}
}

?>