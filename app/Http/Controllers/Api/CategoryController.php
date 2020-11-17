<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\comman_fun;
use Auth;
use Carbon\Carbon;
use App\User;
use App\Category;
use App\Topic;
use App\Slider;
use App\TopicVideo;
use App\TopicDocument;
use App\Package;
use App\ViewCount;
use App\Transaction;
use Str;
use Storage;
use Mail;
use Validator;

class CategoryController extends Controller
{
    use comman_fun;
    public function GetMainCategory(Request $request)
    {
        $user = User::where('phone',$request->mobile_no)->first();
    	$Category = Category::select('id','title','image','description','created_at','updated_at')->where('parent_id', 0)->get();
        if($Category->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No Category Found.',
            ], 200);
        }
        $Category = $Category->map(function ($Cate)use ($user){
            $is_payment = 0;
            $is_running = 1;
            $getAllPackage = Package::where('is_active',1)->where('main_category_id',$Cate->id)->get();
            if(!$getAllPackage->isEmpty())
            {
                foreach ($getAllPackage as $key => $Package) {
                    $Transaction = Transaction::where('package_id',$Package->id)->where('user_id',$user->id)->first();
                    // where('expiry_date','>',date('Y-m-d H:i:s'))
                    if($Transaction)
                    {
                        if($Transaction->expiry_date > date('Y-m-d H:i:s'))
                        {
                            if($Transaction->is_expire != 1)
                            {
                                $is_payment = 1;
                                $is_running = 1;
                            }
                            else
                            {
                                $is_payment = 1;
                                $is_running = 1;
                            }
                            
                        }
                        else
                        {
                            $is_payment = 0;
                            $is_running = 0;
                        }
                    }
                    else
                    {
                        $is_payment = 0;
                        $is_running = 1;
                    }
                }
            }
            $Cate->is_payment = $is_payment;
            $Cate->is_running = $is_running;
            return $Cate;
        });
    	return response()->json([
            'status'=>'6',
            'message' => 'Category Data.',
            'user_data' => $this->setData($Category->toArray()),
        ], 200);
    }
    public function GetSubCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '7',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $SubCategory = Category::select('id','title','created_at','updated_at')->where('parent_id', $request->category_id)->where('sub_parent_id', 0)->get();
        if($SubCategory->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No Sub Category Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'6',
            'message' => 'Sub Category Data.',
            'data' => $this->setData($SubCategory->toArray()),
        ], 200);
    }
    public function GetChildCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '7',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $SubCategory = Category::select('id','title','created_at','updated_at')->where('parent_id', $request->category_id)->where('sub_parent_id', $request->sub_category_id)->get();
        if($SubCategory->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No Child Category Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'6',
            'message' => 'Child Category Data.',
            'data' => $this->setData($SubCategory->toArray()),
        ], 200);
    }
    public function GetTopic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'child_category_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '7',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $Topic = Topic::select('id','title','created_at','updated_at')->where('category_id', $request->category_id)->where('sub_category_id', $request->sub_category_id)->where('child_category_id', $request->child_category_id)->get();
        if($Topic->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No Topic Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'6',
            'message' => 'Topic Data.',
            'data' => $this->setData($Topic->toArray()),
        ], 200);
    }
    public function GetVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '7',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $Videos = TopicVideo::select('id','topic_id','title','video','image','is_paid','created_at','updated_at')->where('topic_id', $request->topic_id)->get();
        if($Videos->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No Video Found.',
            ], 200);
        }
        $Videos = $Videos->map(function ($Video){
            $Video->view_count = ViewCount::where('for_id',$Video->id)->where('type',1)->count();
            return $Video;
        });
        return response()->json([
            'status'=>'6',
            'message' => 'Video Data.',
            'data' => $this->setData($Videos->toArray()),
        ], 200);
    }
    public function GetDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '7',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $Documents = TopicDocument::select('id','topic_id','title','document','image','is_paid','created_at','updated_at')->where('topic_id', $request->topic_id)->get();
        if($Documents->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No Document Found.',
            ], 200);
        }
        $Documents = $Documents->map(function ($Document){
            $Document->view_count = ViewCount::where('for_id',$Document->id)->where('type',2)->count();
            return $Document;
        });
        return response()->json([
            'status'=>'6',
            'message' => 'Document Data.',
            'data' => $this->setData($Documents->toArray()),
        ], 200);
    }
    public function GetPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '7',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $current_date = date('Y-m-d H:i:s');
        // ->where('start_date_time','<',$current_date)->where('end_date_time','>',$current_date)
        $Packages = Package::select('id','title','amount','main_category_id','package_mrp','package_offer','about_course_description','author_name','author_profile_pic','author_designation','author_qualification','time_frame','end_date_time','created_at','updated_at')->where('main_category_id', $request->category_id)->where('is_active',1)
        ->with(['Category'=>function($query){
            $query->select(['id','title','image','description','is_active','created_at','updated_at']);
        },'DemoArticle'=>function($query){
            $query->select(['id','package_id','image','file','is_active','created_at','updated_at']);
        },'DemoVideo'=>function($query){
            $query->select(['id','package_id','image','file','is_active','created_at','updated_at']);
        }])->get();

        $Packages = $Packages->map(function ($package){
            $topic_ids = $package->topic->pluck('id')->toArray();
            $topic_video_count = \DB::table('topic_video')->selectRaw('count(id) as topic_video_count')->whereIn('topic_id', $topic_ids)->whereNull('deleted_at')->first();
            $package->topic_video_count = $topic_video_count->topic_video_count;

            $topic_document_count = \DB::table('topic_document')->selectRaw('count(id) as topic_document_count')->whereIn('topic_id', $topic_ids)->whereNull('deleted_at')->first();
            $package->topic_document_count = $topic_document_count->topic_document_count;
            $package_type = '';
            $expiry_date = Carbon::now();
            if($package->time_frame == 1)
            {
                $package_type = '1 Month';
                $expiry_date->addMonths(1);
            }
            elseif($package->time_frame == 2)
            {
                $package_type = '3 Month';
                $expiry_date->addMonths(3);
            }
            elseif($package->time_frame == 3)
            {
                $package_type = '6 Month';
                $expiry_date->addMonths(6);
            }
            elseif($package->time_frame == 4)
            {
                $package_type = '1 Year';
                $expiry_date->addYears(1);
            }
            $package->end_date_time = $expiry_date->format('Y-m-d').' 23:59:59';
            $package->package_type = $package_type;
            unset($package->topic);
            unset($package->time_frame);
            return $package;
        });

        if($Packages->isEmpty())
        {
            return response()->json([
                'status'=>'5',
                'message' => 'No Package Found.',
            ], 200);
        }
        return response()->json([
            'status'=>'6',
            'message' => 'Package Data.',
            'data' => $this->setData($Packages->toArray()),
        ], 200);
    }
    public function VideoCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        $check_video_availability = TopicVideo::where('id',$request->video_id)->where('is_active',1)->count();
        if($check_video_availability == 0)
        {
            return response()->json([
                'status'=>'6',
                'message' => 'Video not found.',
            ], 200);
        }
        $check_video_count = ViewCount::where('for_id',$request->video_id)->where('user_id',$user->id)->where('type',1)->count();
        if($check_video_count == 0)
        {
            $ViewCount = ViewCount::create([
                'type' => 1,
                'for_id' => $request->video_id,
                'user_id' => $user->id,
            ]);
        }
        return response()->json([
            'status'=>'7',
            'message' => 'Successfully increment.',
        ], 200);
    }
    public function DocumentCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => '5',
                'message' => $validator->messages()->toArray()
            ], 200);
        }
        $user = User::where('phone',$request->mobile_no)->first();
        $check_document_availability = TopicDocument::where('id',$request->document_id)->where('is_active',1)->count();
        if($check_document_availability == 0)
        {
            return response()->json([
                'status'=>'6',
                'message' => 'Document not found.',
            ], 200);
        }
        $check_document_count = ViewCount::where('for_id',$request->document_id)->where('user_id',$user->id)->where('type',2)->count();
        if($check_document_count == 0)
        {
            $ViewCount = ViewCount::create([
                'type' => 2,
                'for_id' => $request->document_id,
                'user_id' => $user->id,
            ]);
        }
        return response()->json([
            'status'=>'7',
            'message' => 'Successfully increment.',
        ], 200);
    }
}
