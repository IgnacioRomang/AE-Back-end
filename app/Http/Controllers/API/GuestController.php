<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PdfDocument;
use App\Models\Question;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:api');
    }
    public function getPdf(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $pdfDocument = PdfDocument::findOrFail($request->id);
            return response()->json($pdfDocument, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'PDF not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }


    public function getPdfList()
    {
        $pdfDocuments = PdfDocument::all();
        if ($pdfDocuments->isEmpty()) {
            return response()->json([
                'message' => 'No PDFs found',
            ], Response::HTTP_NOT_FOUND);
        }
        $pdfList = $pdfDocuments->map(function ($pdfDocument) {
            return [
                'id' => $pdfDocument->id,
                'title' => $pdfDocument->title,
                'abstract' => $pdfDocument->abstract,
                'img' => $pdfDocument->img,
            ];
        })->all();
        return response()->json(
        $pdfList
        , Response::HTTP_OK);
    }

    public function publishPDF(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'img' => 'required',
            'content' => 'required',
        ]);
        try {
            $pdf = PdfDocument::create([
                'title' => $request->input('title'),
                'abstract' => $request->input('abstract'),
                'img' => $request->input('img'),
                'content' => $request->input('content'),
            ]);
            return response()->json([
                'id' => $pdf->id,
                'title' => $pdf->title,
                'abstract' => $pdf->abstract,
                'content' => $pdf->content,
                'created_at' => $pdf->created_at,
                'msg' => 'PDF has been created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create PDF'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getQuestionList()
    {
        $questions = Question::all();

        return $questions->isEmpty()
            ? response()->json(['message' => 'No questions found'], Response::HTTP_NOT_FOUND)
            : response()->json($questions, Response::HTTP_OK);
    }
}
