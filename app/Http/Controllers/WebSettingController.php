<?php

namespace App\Http\Controllers;

use App\Models\BasicSetting;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Partner;
use App\Models\Slider;
use App\Models\Social;
use App\Models\Speciality;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class WebSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function manageLogo()
    {
        $data['page_title'] = "Manage Logo & Favicon";

        return view('webControl.logo', $data);
    }

    /**
     * @param Request $request
     */
    public function updateLogo(Request $request)
    {

        $this->validate($request, [
            'logo'    => 'mimes:png',
            'favicon' => 'mimes:png'
        ]);
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = 'logo' . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/' . $filename);
            Image::make($image)->save($location);
        }
        if ($request->hasFile('favicon')) {
            $image = $request->file('favicon');
            $filename = 'favicon' . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/' . $filename);
            Image::make($image)->resize(50, 50)->save($location);
        }
        if ($request->hasFile('loader')) {
            $image = $request->file('loader');
            $filename = 'loader' . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/');
            $image->move($location, $filename);
        }
        if ($request->hasFile('footer-logo')) {
            $image = $request->file('footer-logo');
            $filename = 'footer-logo' . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/' . $filename);
            Image::make($image)->save($location);
        }
        session()->flash('message', 'Logo and Favicon Updated Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function manageFooter()
    {
        $data['page_title'] = "Manage Web Footer";

        return view('webControl.footer', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateFooter(Request $request, $id)
    {
        $basic = BasicSetting::findOrFail($id);
        $this->validate($request, [
            'footer_text'  => 'required',
            'copy_text'    => 'required',
            'footer_image' => 'mimes:png,jpg,jpeg'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('footer_image')) {
            $image = $request->file('footer_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/' . $filename);
            Image::make($image)->resize(1600, 475)->save($location);
            $path = 'assets/images/';
            $link = public_path($path . $basic->footer_image);
            File::delete($link);
            $in['footer_image'] = $filename;
        }
        $basic->fill($in)->save();
        session()->flash('message', 'Web Footer Updated Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function manageSlider()
    {
        $data['page_title'] = "Manage Slider";
        $data['slider'] = Slider::all();

        return view('webControl.slider', $data);
    }

    /**
     * @param Request $request
     */
    public function storeSlider(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:png,jpeg,jpg,gif'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'slider_' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/slider/' . $filename);
            Image::make($image)->resize(1900, 730)->save($location);
            $in['image'] = $filename;
        }
        Slider::create($in);
        session()->flash('message', 'Slider Created Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->route('manage-slider');
    }

    /**
     * @param $id
     */
    public function editSlider($id)
    {
        $data['page_title'] = "Edit Slider";
        $data['slider'] = Slider::all();
        $data['sli'] = Slider::findOrFail($id);

        return view('webControl.slider-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateSlider(Request $request, $id)
    {
        $sli = Slider::findOrFail($id);
        $this->validate($request, [
            'image' => 'mimes:png,jpeg,jpg,gif'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'slider_' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/slider/' . $filename);
            Image::make($image)->resize(1900, 730)->save($location);
            File::delete(public_path("assets/images/slider/$sli->image"));
            $in['image'] = $filename;
        }
        $sli->update($in);
        session()->flash('message', 'Slider Update Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->route('manage-slider');
    }

    /**
     * @param Request $request
     */
    public function deleteSlider(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $slider = Slider::findOrFail($request->id);
        $path = 'assets/images/slider/';
        $link = public_path($path . $slider->image);
        $slider->delete();
        File::delete($link);
        session()->flash('message', 'Slider Deleted Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->route('manage-slider');
    }

    public function manageSocial()
    {
        $data['page_title'] = "Manage Social";
        $data['social'] = Social::all();

        return view('webControl.social', $data);
    }

    /**
     * @param Request $request
     */
    public function storeSocial(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'link' => 'required'
        ]);

        $product = Social::create($request->input());

        return response()->json($product);

    }

    /**
     * @param $product_id
     */
    public function editSocial($product_id)
    {
        $product = Social::find($product_id);

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @param $product_id
     */
    public function updateSocial(Request $request, $product_id)
    {
        $product = Social::find($product_id);
        $product->name = $request->name;
        $product->code = $request->code;
        $product->link = $request->link;
        $product->save();

        return response()->json($product);
    }

    /**
     * @param $product_id
     */
    public function deleteSocial($product_id)
    {
        $product = Social::destroy($product_id);

        return response()->json($product);
    }

    public function manageMenu()
    {
        $data['page_title'] = "Control Menu";
        $data['menu'] = Menu::all();

        return view('webControl.menu-show', $data);
    }

    public function createMenu()
    {
        $data['page_title'] = "Create Menu";

        return view('webControl.menu-create', $data);
    }

    /**
     * @param Request $request
     */
    public function storeMenu(Request $request)
    {
        $this->validate($request, [
            'name'        => 'required|unique:menus,name',
            'description' => 'required'
        ]);
        $in = $request->except('_method', '_token');
        $in['slug'] = str_slug($request->name);
        Menu::create($in);
        session()->flash('message', 'Menu Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param $id
     */
    public function editMenu($id)
    {
        $data['page_title'] = "EdIt MEnu";
        $data['menu'] = Menu::findOrFail($id);

        return view('webControl.menu-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $this->validate($request, [
            'name'        => 'required|unique:menus,name,' . $menu->id,
            'description' => 'required'
        ]);
        $in = $request->except('_method', '_token');
        $in['slug'] = str_slug($request->name);
        $menu->fill($in)->save();
        session()->flash('message', 'Menu Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function deleteMenu(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        Menu::destroy($request->id);
        session()->flash('message', 'Menu Deleted Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function mangeBreadcrumb()
    {
        $data['page_title'] = "Manage Breadcrumb";

        return view('webControl.breadcrumb', $data);
    }

    /**
     * @param Request $request
     */
    public function updateBreadcrumb(Request $request)
    {
        $basic = BasicSetting::first();
        $this->validate($request, [
            'breadcrumb' => 'mimes:png,jpg,jpeg'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('breadcrumb')) {
            $image = $request->file('breadcrumb');
            $filename = 'breadcrumb_' . time() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(1970, 260)->save(public_path($location));
            $path = 'assets/images/';
            $link = public_path($path . $basic->breadcrumb);
            File::delete($link);
            $in['breadcrumb'] = $filename;
        }
        $basic->fill($in)->save();
        session()->flash('message', 'Breadcrumb Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function manageAbout()
    {
        $data['page_title'] = "Manage About";

        return view('webControl.about', $data);
    }

    /**
     * @param Request $request
     */
    public function updateAbout(Request $request)
    {
        $this->validate($request, [
            'about' => 'required'
        ]);
        $basic = BasicSetting::first();
        $basic->about = $request->about;
        $basic->save();
        session()->flash('message', 'About Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function managePrivacyPolicy()
    {
        $data['page_title'] = "Manage Privacy Policy";

        return view('webControl.privacy-policy', $data);
    }

    /**
     * @param Request $request
     */
    public function updatePrivacyPolicy(Request $request)
    {
        $this->validate($request, [
            'about' => 'required'
        ]);
        $basic = BasicSetting::first();
        $basic->privacy = $request->about;
        $basic->save();
        session()->flash('message', 'Privacy Policy Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function manageTermsCondition()
    {
        $data['page_title'] = "Terms Condition About";

        return view('webControl.terms-condition', $data);
    }

    /**
     * @param Request $request
     */
    public function updateTermsCondition(Request $request)
    {
        $this->validate($request, [
            'about' => 'required'
        ]);
        $basic = BasicSetting::first();
        $basic->terms = $request->about;
        $basic->save();
        session()->flash('message', 'Terms Condition Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function createTestimonial()
    {
        $data['page_title'] = "Create New Testimonial";

        return view('webControl.testimonial-create', $data);
    }

    /**
     * @param Request $request
     */
    public function submitTestimonial(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'image'   => 'required|mimes:png,jpeg,jpg',
            'message' => 'required'
        ]);
        $in = $request->except('_method', '_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'testimonial_' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/testimonial/' . $filename);
            Image::make($image)->resize(180, 180)->save($location);
            $in['image'] = $filename;
        }
        Testimonial::create($in);
        session()->flash('message', 'Testimonial Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function allTestimonial()
    {
        $data['page_title'] = "All Testimonial";
        $data['testimonial'] = Testimonial::orderBy('id', 'desc')->paginate(10);

        return view('webControl.testimonial-all', $data);
    }

    /**
     * @param $id
     */
    public function editTestimonial($id)
    {
        $data['page_title'] = "Edit Testimonial";
        $data['testimonial'] = Testimonial::findOrFail($id);

        return view('webControl.testimonial-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateTestimonial(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $request->validate([
            'name'    => 'required',
            'image'   => 'mimes:png,jpeg,jpg',
            'message' => 'required'
        ]);
        $in = $request->except('_method', '_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'testimonial_' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/testimonial/' . $filename);
            Image::make($image)->resize(180, 180)->save($location);
            $path = 'assets/images/testimonial/';
            $link = public_path($path . $testimonial->image);
            File::delete($link);
            $in['image'] = $filename;
        }
        $testimonial->fill($in)->save();
        session()->flash('message', 'Testimonial Update Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function deleteTestimonial(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $testimonial = Testimonial::findOrFail($request->id);
        $path = 'assets/images/';
        $link = public_path($path . $testimonial->image);
        File::delete($link);
        $testimonial->delete();
        session()->flash('message', 'Testimonial Deleted Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function createPartner()
    {
        $data['page_title'] = "Create New Partner";

        return view('webControl.partner-create', $data);
    }

    /**
     * @param Request $request
     */
    public function submitPartner(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'required|mimes:png,jpeg,jpg'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'partner_' . time() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/partner/' . $filename;
            Image::make($image)->resize(350, 120)->save(public_path($location));
            $in['image'] = $filename;
        }
        Partner::create($in);
        session()->flash('message', 'Partner Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function allPartner()
    {
        $data['page_title'] = "All Partner";
        $data['testimonial'] = Partner::orderBy('id', 'desc')->paginate(10);

        return view('webControl.partner-all', $data);
    }

    /**
     * @param $id
     */
    public function editPartner($id)
    {
        $data['page_title'] = "Edit Partner";
        $data['testimonial'] = Partner::findOrFail($id);

        return view('webControl.partner-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updatePartner(Request $request, $id)
    {
        $testimonial = Partner::findOrFail($id);
        $request->validate([
            'name'  => 'required',
            'image' => 'mimes:png,jpeg,jpg'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'partner_' . time() . '.' . $image->getClientOriginalExtension();
            $location = 'assets/images/partner/' . $filename;
            Image::make($image)->resize(350, 120)->save(public_path($location));
            $path = 'assets/images/partner/';
            $link = $path . $testimonial->image;
            File::delete(public_path($link));
            $in['image'] = $filename;
        }
        $testimonial->fill($in)->save();
        session()->flash('message', 'Partner Update Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function deletePartner(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $testimonial = Partner::findOrFail($request->id);
        $path = 'assets/images/partner/';
        $link = $path . $testimonial->image;
        File::delete(public_path($link));
        $testimonial->delete();
        session()->flash('message', 'Partner Deleted Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function manageAboutText()
    {
        $data['page_title'] = "Manage About Text";

        return view('webControl.about-text', $data);
    }

    /**
     * @param Request $request
     */
    public function updateAboutText(Request $request)
    {
        $page = BasicSetting::first();
        $in = $request->except('_method', '_token');
        $page->fill($in)->save();
        session()->flash('message', 'About Text Update Successfully.');
        session()->flash('title', 'Success');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function manageSpeciality()
    {
        $data['page_title'] = "Control Speciality";
        $data['menu'] = Speciality::all();

        return view('webControl.speciality-show', $data);
    }

    public function createSpeciality()
    {
        $data['page_title'] = "Create Speciality";

        return view('webControl.speciality-create', $data);
    }

    /**
     * @param Request $request
     */
    public function storeSpeciality(Request $request)
    {
        $this->validate($request, [
            'name'        => 'required|unique:specialities,name',
            'description' => 'required'
        ]);

        $in = $request->except('_method', '_token');
        Speciality::create($in);

        session()->flash('message', 'Speciality Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param $id
     */
    public function editSpeciality($id)
    {
        $data['page_title'] = "Edit Speciality";
        $data['menu'] = Speciality::findOrFail($id);

        return view('webControl.speciality-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateSpeciality(Request $request, $id)
    {
        $menu = Speciality::findOrFail($id);
        $this->validate($request, [
            'name'        => 'required|unique:specialities,name,' . $menu->id,
            'description' => 'required'
        ]);
        // $menu->fill($request->all())->save();
        $in = $request->except('_method', '_token', 'id');
        $menu->update($in);

        session()->flash('message', 'Speciality Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function deleteSpeciality(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        Speciality::destroy($request->id);
        session()->flash('message', 'Speciality Deleted Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function createMember()
    {
        $data['page_title'] = "Create New Testimonial";

        return view('webControl.member-create', $data);
    }

    /**
     * @param Request $request
     */
    public function submitMember(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'image'      => 'required|mimes:png,jpeg,jpg',
            'details'    => 'required',
            'facebook'   => 'required',
            'twitter'    => 'required',
            'linkedin'   => 'required',
            'instragram' => 'required'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'member_' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/member/' . $filename);
            Image::make($image)->resize(360, 380)->save($location);
            $in['image'] = $filename;
        }
        Member::create($in);
        session()->flash('message', 'Member Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function allMember()
    {
        $data['page_title'] = "All Member";
        $data['testimonial'] = Member::orderBy('id', 'desc')->paginate(10);

        return view('webControl.member-all', $data);
    }

    /**
     * @param $id
     */
    public function editMember($id)
    {
        $data['page_title'] = "Edit Member";
        $data['testimonial'] = Member::findOrFail($id);

        return view('webControl.member-edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateMember(Request $request, $id)
    {
        $testimonial = Member::findOrFail($id);
        $request->validate([
            'name'       => 'required',
            'image'      => 'mimes:png,jpeg,jpg',
            'details'    => 'required',
            'facebook'   => 'required',
            'twitter'    => 'required',
            'linkedin'   => 'required',
            'instragram' => 'required'
        ]);
        $in = $request->except('_method', '_token');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'member_' . time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('assets/images/member/' . $filename);
            Image::make($image)->resize(360, 380)->save($location);
            $path = 'assets/images/member/';
            $link = public_path($path . $testimonial->image);
            File::delete($link);
            $in['image'] = $filename;
        }
        $testimonial->fill($in)->save();
        session()->flash('message', 'Member Update Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function deleteMember(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $testimonial = Member::findOrFail($request->id);
        $path = 'assets/images/member/';
        $link = $path . $testimonial->image;
        File::delete(public_path($link));
        $testimonial->delete();
        session()->flash('message', 'Member Deleted Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

    public function shortAbout()
    {
        $data['page_title'] = "Sort About";

        return view('webControl.short-about', $data);
    }

    /**
     * @param Request $request
     */
    public function updateShortAbout(Request $request)
    {
        $data = BasicSetting::find($request->id);
        $request->validate([
            'short_about'     => 'required',
            'short_title'     => 'required',
            'short_about_img' => 'mimes:png,jpeg,jpg'
        ]);

        $in['short_about'] = $request->short_about;
        $in['short_title'] = $request->short_title;
        if ($request->hasFile('short_about_img')) {
            File::delete(('assets/images') . '/' . $data->short_about_img);
            $image = $request->file('short_about_img');
            $image_name = str_random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . '.' . $ext;
            $location = ('assets/images') . '/' . $image_full_name;
            Image::make($image)->resize(750, 720)->save(public_path($location));
            $in['short_about_img'] = $image_full_name;
        }
        $data->update($in);
        session()->flash('message', 'Short About Update Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');

        return redirect()->back();
    }

}
