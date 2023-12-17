<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\answere;
use App\Models\category;
use App\Models\questions;
use App\Models\Story;
use App\Models\sub_category;
use Illuminate\Http\Request;

class quizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    // CATEGORY //

    public function Category(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser) {
            $category = category::create([
                'category' => $request->input('category'),
            ]);
            if ($category) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Category add successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'plese login',
            ]);
        }
    }

    public function editCategory($id)
    {
        $authUser = auth()->user();
        if ($authUser) {
            $editCategory = category::where('id', $id)->get();
            if ($editCategory) {
                return response()->json([
                    'status' => 'success',
                    'Category' => $editCategory,
                ]);
            }
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'plese login',
            ]);
        }
    }

    public function updateCategory(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser) {
            $category = category::find($request->id);
            $category->category = $request->category;
            $category->save();
            if ($category) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Category update success',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'flase',
                'message' => 'Plese login you',
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $authUser = auth()->user();
        if ($authUser) {
            $deleteCategory = category::where('id', $id)->delete();
            if ($deleteCategory) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Delete success fully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'flase',
                'message' => 'Plese login you',
            ]);
        }
    }

    public function getCategory()
    {
        $authUser = auth()->user();
        if ($authUser) {
            $category = category::all();
            if ($category) {
                return response()->json([
                    'status' => 'success',
                    'Category' => $category,
                ]);
            }
        } else {
            return response()->json([
                'status' => 'flase',
                'message' => 'Plese login you',
            ]);
        }
    }

    // --------------SUB CATEGORY---------- //

    public function subCategory(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser) {
            $category = sub_category::create([
                'category' => $request->input('category'),
                'sub_category' => $request->input('subCategory'),
            ]);
            if ($category) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sub category add successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'flase',
                'message' => 'Plese login you',
            ]);
        }
    }

    public function editSubCategory($id)
    {
        $editSubCategory = sub_category::where('id', $id)->get();
        if ($editSubCategory) {
            return response()->json([
                'status' => 'success',
                'Sub category' => $editSubCategory,
            ]);
        }
    }

    public function updateSubCategory(Request $request)
    {
        $subCategory = sub_category::find($request->id);
        $subCategory->id = $request->id;
        $subCategory->category = $request->category;
        $subCategory->sub_category = $request->subCategory;
        $subCategory->save();
        if ($subCategory) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sub category update success',
            ]);
        }
    }

    public function deleteSubCategory($id)
    {
        $deleteSubCategory = sub_category::where('id', $id)->delete();
        if ($deleteSubCategory) {
            return response()->json([
                'status' => 'success',
                'message' => 'Delete success fully',
            ]);
        }
    }

    public function getSubCategory()
    {
        $subCategory = sub_category::all();
        if ($subCategory) {
            return response()->json([
                'status' => 'success',
                'Sub category' => $subCategory,
            ]);
        }
    }

    // CATEGORY DEPENDENDT SUB CATEGORY//

    public function categorySubCategory()
    {
        $ParentCategory = category::all();
        $CategoryDetailsArray = [];
        foreach ($ParentCategory as $value) {
            $subCategory = sub_category::where('category', $value['id'])->get();
            $item = [
                'ParentCategoryName' => $value['category'],
                // "ParentCategoryImage"=>$value['cat_images'],
                'subCategory' => $subCategory
            ];
            array_push($CategoryDetailsArray, $item);
        }
        return $CategoryDetailsArray;
    }

    // ====================== ADD QUESTIONS ================== //

    public function questions(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'sub_catergory_id' => 'required',
            'question' => 'required',
            'ans' => 'required',
            'mark' => 'required',
            'imageOne' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageTwo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image1 = time() . '.' . $request->imageOne->extension();
        $request->imageOne->move(public_path('images'), $image1);

        $image2 = time() . '.' . $request->imageTwo->extension();
        $request->imageTwo->move(public_path('images'), $image2);

        $question = questions::create([
            'categoryes' => $request->input('category_id'),
            'sub_catergory' => $request->input('sub_catergory_id'),
            'questions' => $request->input('question'),
            'ans' => $request->input('ans'),
            'mark' => $request->input('mark'),
            'image_one' => $image1,
            'image_two' => $image2,
        ]);
        if ($question) {
            return response()->json([
                'status' => 'success',
                'message' => 'questions add successfully',
            ]);
        }
    }

    public function editQuestion($id)
    {
        $editQuestions = questions::where('id', $id)->get();
        if ($editQuestions) {
            return response()->json([
                'status' => 'success',
                'Questions' => $editQuestions,
            ]);
        }
    }

    public function updateQuestion(Request $request)
    {
        $updateQuestion = questions::find($request->id);
        $updateQuestion->id = $request->id;
        $updateQuestion->categoryes = $request->category_id;
        $updateQuestion->sub_catergory = $request->sub_catergory_id;
        $updateQuestion->questions = $request->question;
        $updateQuestion->ans = $request->ans;
        $updateQuestion->mark = $request->mark;
        $updateQuestion->save();
        if ($updateQuestion) {
            return response()->json([
                'status' => 'success',
                'message' => 'question update success',
            ]);
        }
    }

    public function updateQuestionImg1(Request $request)
    {
        $request->validate([
            'imageOne' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image1 = time() . '.' . $request->imageOne->extension();
        $request->imageOne->move(public_path('images'), $image1);

        $updateImage1 = questions::find($request->id);
        $updateImage1->id = $request->id;
        $updateImage1->image_one = $image1;
        $updateImage1->save();
        if ($updateImage1) {
            return response()->json([
                'status' => 'success',
                'message' => 'Question  imageOne update success',
            ]);
        }
    }

    public function deleteQuestion1Img(Request $request)
    {
        $questionImg1 = questions::find($request->id);
        $questionImg1->id = $request->id;
        if (file_exists('image_one' . $questionImg1->imageOne) AND !empty($questionImg1->image_one)) {
            unlink('image_one' . $questionImg1->imageOne);
        }
        $questionImg1->image_one = '';
        $questionImg1->save();
        if ($questionImg1 == true) {
            return response()->json([
                'status' => 'success',
                'message' => 'questions images one delete success'
            ]);
        } else {
            return response()->json([
                'status' => 'faile',
                'message' => 'questions images one delete faile'
            ]);
        }
    }

    public function updateQuestionImg2(Request $request)
    {
        $request->validate([
            'imageTwo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image2 = time() . '.' . $request->imageTwo->extension();
        $request->imageTwo->move(public_path('images'), $image2);

        $updateImage2 = questions::find($request->id);
        $updateImage2->id = $request->id;
        $updateImage2->image_two = $image2;
        $updateImage2->save();
        if ($updateImage2) {
            return response()->json([
                'status' => 'success',
                'message' => 'Question  image two update success',
            ]);
        }
    }

    public function deleteQuestion2Img(Request $request)
    {
        $questionImg2 = questions::find($request->id);
        $questionImg2->id = $request->id;
        if (file_exists('image_two' . $questionImg2->imageTwo) AND !empty($questionImg2->image_two)) {
            unlink('image_two' . $questionImg2->imageTwo);
        }
        $questionImg2->image_two = '';
        $questionImg2->save();
        if ($questionImg2 == true) {
            return response()->json([
                'status' => 'success',
                'message' => 'questions images two delete success'
            ]);
        } else {
            return response()->json([
                'status' => 'faile',
                'message' => 'questions images two delete faile'
            ]);
        }
    }

    public function removeQuestion($id)
    {
        $removeQuestion = questions::where('id', $id)->delete();
        if ($removeQuestion) {
            return response()->json([
                'status' => 'success',
                'message' => 'Question  delete success',
            ]);
        }
    }

    public function getQuestion()
    {
        $questions = questions::all();
        if ($questions) {
            return response()->json([
                'status' => 'success',
                'Questions' => $questions,
            ]);
        }
    }

    // ======================DISPLAY QUESTION =============//

    public function displayQuestion($id)
    {
        $quize = questions::where('sub_catergory', $id)->get();
        if ($quize) {
            return response()->json([
                'status' => 'success',
                'Quize' => $quize,
            ]);
        }
    }

    // ============ Answare ============//

    public function answare(Request $request)
    {
        $auth = auth()->user();
        $userId = $auth->id;
        // $chooseOne = $request->choosOne;
        // $choosTwo = $request->choosTwo;

        // if ($chooseOne == true) {
        //     $choosTwoNumber = '0';
        //     $chooseOneNumber = $chooseOne;
        // } elseif ($choosTwo == true) {
        //     $chooseOneNumber = '0';
        //     $choosTwoNumber = $choosTwo;
        // } else {
        //     $chooseOneNumber = '0';
        //     $choosTwoNumber = '0';
        // }

        $postAnsewer = answere::create([
            'user_id' => $userId,
            'cate_id' => $request->input('catId'),
            'sub_catecory_id' => $request->input('subCatId'),
            'mark' => $request->input('mark'),
        ]);
        if ($postAnsewer) {
            return response()->json([
                'status' => 'success',
                'message' => 'Answere submiteded',
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Answere submiteded faile',
            ]);
        }
    }

    // ========================STORY ==================//

    public function story(Request $request)
    {
        $request->validate([
            'StoryImg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $avatar = time() . '.' . $request->StoryImg->extension();
        $request->StoryImg->move(public_path('images'), $avatar);
        $auth = auth()->user();
        $userName = $auth->name;
        $story = Story::create([
            'username' => $userName,
            'description' => $request->input('description'),
            'avatar' => $avatar,
            'servay_name' => $request->servayName,
        ]);

        if ($story) {
            return response()->json([
                'status' => 'success',
                'message' => 'Story add successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Story add faile',
            ]);
        }
    }

    public function getStory()
    {
        $getStory = Story::all();
        if ($getStory) {
            return response()->json([
                'status' => 'success',
                'Sub category' => $getStory,
            ]);
        }
    }

    public function editStory($id)
    {
        $editStory = Story::where('id', $id)->first();
        if ($editStory) {
            return response()->json([
                'status' => 'success',
                'Story' => $editStory,
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'error',
            ]);
        }
    }

    public function updateStory(Request $request)
    {
        $updateStory = Story::find($request->id);
        $updateStory->id = $request->id;
        $auth = auth()->user();
        $userName = $auth->name;
        $updateStory->username = $userName;
        $updateStory->description = $request->description;
        $updateStory->servay_name = $request->servay_name;
        $updateStory->save();
        if ($updateStory) {
            return response()->json([
                'status' => 'success',
                'message' => 'Story update success',
            ]);
        } else {
            return response()->json([
                'status' => 'faile',
                'message' => 'Server error 500',
            ]);
        }
    }

    public function removeStory($id)
    {
        $removeStory = Story::where('id', $id)->delete();
        if ($removeStory) {
            return response()->json([
                'status' => 'success',
                'message' => 'Story  delete success',
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'internal server error',
            ]);
        }
    }

    public function updateStoryImg(Request $request)
    {
        $request->validate([
            'StoryImg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $avatar = time() . '.' . $request->StoryImg->extension();
        $request->StoryImg->move(public_path('images'), $avatar);

        $updateStoryImg = Story::find($request->id);
        $updateStoryImg->id = $request->id;
        $updateStoryImg->avatar = $avatar;
        $updateStoryImg->save();
        if ($updateStoryImg) {
            return response()->json([
                'status' => 'success',
                'message' => 'Story image update success',
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Story image update faile',
            ]);
        }
    }

    public function deleteStoryImg(Request $request)
    {
        $deleteStoryImg = Story::find($request->id);
        $deleteStoryImg->id = $request->id;
        if (file_exists('avatar' . $deleteStoryImg->avatar) AND !empty($deleteStoryImg->avatar)) {
            unlink('avatar' . $deleteStoryImg->avatar);
        }
        $deleteStoryImg->avatar = '';
        $deleteStoryImg->save();
        if ($deleteStoryImg == true) {
            return response()->json([
                'status' => 'success',
                'message' => 'Story images  delete success'
            ]);
        } else {
            return response()->json([
                'status' => 'faile',
                'message' => 'Story images  delete faile'
            ]);
        }
    }

    public function comments()
    {
        
    }
}
