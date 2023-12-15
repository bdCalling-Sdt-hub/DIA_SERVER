<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    //

    public function addStory(Request $request){
        $validator = Validator::make($request->all(), [
            'story_image' => 'required|string',
            'story_description' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $story = new Story();

        $story->user_id = auth()->user()->id;
        $story->story_image = $request->story_image;
        $story->story_description = $request->story_description;
        $story->save();
        return response()->json([
            'message' => 'story added successfully',
            'story' => $story,
        ]);



//            $story = auth()->user()->stories()->create([
//                'story_image' => $imagePath,
//                'story_description' => $request->input('story_description'),
//            ]);
//
//            return redirect()->route('stories.index')->with('success', 'Story uploaded successfully.');
        }

//        public function comment(Request $request, Story $story)
//        {
//            $request->validate([
//                'comment_text' => 'required',
//            ]);
//
//            $comment = $story->comments()->create([
//                'user_id' => auth()->id(),
//                'comment_text' => $request->input('comment_text'),
//            ]);
//
//            // Notify the story owner
//            Notification::create([
//                'user_id' => $story->user->id,
//                'story_id' => $story->id,
//                'message' => auth()->user()->name . ' commented on your story.',
//            ]);
//
//            return redirect()->back()->with('success', 'Comment added successfully.');
//        }

}
