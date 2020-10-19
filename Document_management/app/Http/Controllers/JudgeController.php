<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Carbon\Carbon;

class JudgeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
    //judges documents view for review
    function judge_review()
    {
      $all_review_doc = Document::all();
      return view('judge_utilities.review_document', compact('all_review_doc'));
    }

    //Document Approve
    function document_approve(Request $request)
    {
      // $socument = Document::findOrFail($request->id);
      $approveVal = $request->approve;
      if ($approveVal == 'on') {
        $approveVal = 2;
      }
      else {
        $approveVal = 0;
      }
      Document::findOrFail($request->id)->update([
        'approval_status' =>$approveVal,
        'published_date' => Carbon::now(),
      ]);
      toastr()->success('The document has been waiting for admin approval!');
      return back();
    }

    //Document Detail
    function document_detail($id)
    {
      $single_document = Document::findOrFail($id);
      return view('judge_utilities.document_detail', compact('single_document'));
    }

    //Comment form view
    function comment_form($id)
    {
        $single_reply = Document::findOrFail($id);
        return view('judge_utilities.comment_form',compact('single_reply'));
    }

    //Judges Comment send
    function comment_send(Request $request)
    {
      $request->validate([
        'technical_quality' => 'required',
        'presentation_quality' => 'required',
        'clarity' => 'required',
        'reference_survey' => 'required',
        'relevance' => 'required',
      ]);
        $technical_quality = $request['technical_quality'];
        $presentation_quality = $request['presentation_quality'];
        $clarity = $request['clarity'];
        $reference_survey = $request['reference_survey'];
        $relevance = $request['relevance'];
        Document::findOrFail($request->id)->update([
          'technical_quality' => $technical_quality,
          'presentaion_quality' => $presentation_quality,
          'clarity' => $clarity,
          'reference_survey' => $reference_survey,
          'relevance' => $relevance,
          'author_message' => $request->author_message,
        ]);
        toastr()->success('Feedback has been send!');
        return redirect(route('judge_review_document'));
    }
}
