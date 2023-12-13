<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\questions;
use App\Models\sub_category;
use Illuminate\Http\Request;

class quizeController extends Controller
{
    // CATEGORY //

    public function Category(Request $request)
    {
        $category = category::create([
            'category' => $request->input('category'),
        ]);
        if ($category) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category add successfully',
            ]);
        }
    }

    public function editCategory($id)
    {
        $editCategory = category::where('id', $id)->get();
        if ($editCategory) {
            return response()->json([
                'status' => 'success',
                'Category' => $editCategory,
            ]);
        }
    }

    public function updateCategory(Request $request)
    {
        $category = category::find($request->id);
        $category->category = $request->category;
        $category->save();
        if ($category) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category update success',
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $deleteCategory = category::where('id', $id)->delete();
        if ($deleteCategory) {
            return response()->json([
                'status' => 'success',
                'message' => 'Delete success fully',
            ]);
        }
    }

    public function getCategory()
    {
        $category = category::all();
        if ($category) {
            return response()->json([
                'status' => 'success',
                'Category' => $category,
            ]);
        }
    }

    // --------------SUB CATEGORY---------- //

    public function subCategory(Request $request)
    {
        $category = sub_category::create([
            'category_id' => $request->input('category'),
            'sub_category' => $request->input('subCategory'),
        ]);
        if ($category) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sub category add successfully',
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
        $subCategory->category_id = $request->category;
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
        $updateQuestion->categoryes = $request->category_id;
        $updateQuestion->sub_catergory =$request->sub_catergory_id;
        $updateQuestion->questions = $request->question;
        $updateQuestion->ans = $request->ans;
        $updateQuestion->mark = $request->mark;
        $updateQuestion->save();
        if ($subCategory) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sub category update success',
            ]);
        }
    }

    public function inmageOneRemove($id)
    {
        questions::where('image_one',$id)->delete();
    }
}
