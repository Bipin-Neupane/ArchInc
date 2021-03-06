<?php

namespace App\Http\Controllers\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CMS\CreateAboutRequest;
use App\Models\CMS\About;
use Session;

class AboutController extends Controller {
	public $model;

	public function __construct(About $model) {
		$this->model = $model;

	}

	public function index() {
		$data = $this->model->paginate(4);

		return view('backend.CMS.About.index', compact('data'));
	}

	public function create() {
		return view('backend.CMS.About.create');
	}

	public function store(CreateAboutRequest $request) {

		$data = $request->all();

		$latest = $this->model->create($data);

		Session::flash('flash_success', 'The Profile has been craeted!.');
		Session::flash('flash_type', 'alert-success');

		return redirect()->back();
	}

	public function edit($id) {

		$data = $this->model->find($id);
		return view('backend.CMS.About.edit', compact('data'));
	}

	public function update($id, CreateAboutRequest $request) {

		$data = $request->all();
		$this->model->find($id)->update($request->all());
		Session::flash('flash_success', 'The Profile has been Updated!.');
		Session::flash('flash_type', 'alert-success');
		return redirect()->back();
	}

	public function delete($id) {

		$this->model->find($id)->delete();
		return redirect()->back();
		Session::flash('flash_success', 'The Profile has been Deleted!.');
		Session::flash('flash_type', 'alert-success');

	}

}
