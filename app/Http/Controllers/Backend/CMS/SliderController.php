<?php

namespace App\Http\Controllers\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CMS\CreateSliderRequest;
use App\Models\CMS\CmsSlider;
use Illuminate\Support\Facades\Auth;
use Session;

class SliderController extends Controller {
	public $model;

	public function __construct(CmsSlider $model) {
		$this->model = $model;

	}

	public function index() {
		$data = $this->model->paginate(4);

		return view('backend.CMS.slider.index', compact('data'));
	}

	public function create() {
		return view('backend.CMS.slider.create');
	}

	public function store(CreateSliderRequest $request) {

		$data = $request->all();

		$user_id = Auth::User()->id;
		$photo = $request->file('image');
		$originalName = $photo->getClientOriginalName();
		$extension = $photo->getClientOriginalExtension();
		$size = $photo->getClientSize();
		$originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - strlen($extension) - 1);
		$number = mt_rand(100000, 999999);
		$filename = $originalNameWithoutExt . '-' . $number . '.' . $extension;
		$photo = $request->file('image');

		$p = $photo->move(
			base_path() . '/public/uploads/', $filename
		);

		$data = [

			'title' => $request->title,
			'description' => $request->description,
			'image' => $filename,
			'user_id' => $user_id,

		];

		$latest = $this->model->create($data);

		Session::flash('flash_success', 'The Profile has been Created!.');
		Session::flash('flash_type', 'alert-success');

		return redirect()->back();
	}

	public function edit($id) {

		$data = $this->model->find($id);
		return view('backend.CMS.slider.edit', compact('data'));
	}

	public function update($id, CreateSliderRequest $request) {

		if ($request->hasFile('image')) {

			$photo = $request->file('image');
			$user_id = Auth::User()->id;

			$originalName = $photo->getClientOriginalName();

			$extension = $photo->getClientOriginalExtension();
			$size = $photo->getClientSize();
			$originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - strlen($extension) - 1);

			$filename = $originalNameWithoutExt . '-' . '-' . "." . $extension;
			$photo = $request->file('image');

			$p = $photo->move(
				base_path() . '/public/uploads/', $filename
			);

			$data = [

				'title' => $request->title,
				'description' => $request->description,
				'image' => $filename,
				'user_id' => $user_id,

			];

			$this->model->find($id)->update($data);
		} else {

			$this->model->find($id)->update($request->all());
		}

		return redirect()->back();
		Session::flash('flash_success', 'The Profile has been Updated!.');
		Session::flash('flash_type', 'alert-success');

	}

	public function delete($id) {

		$this->model->find($id)->delete();
		return redirect()->back();
		Session::flash('flash_success', 'The Profile has been Deleted!.');
		Session::flash('flash_type', 'alert-success');

	}

}
