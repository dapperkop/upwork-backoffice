<?php

namespace App\Http\Controllers;

use App\Models\BlockedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlockedWordController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query', false);
        $blockedWordsQuery = BlockedWord::orderBy('content', 'asc');

        if ($query) {
            $blockedWordsQuery = $blockedWordsQuery->whereRaw('LOWER(`content`) LIKE ?', '%' . strtolower($query) . '%');
        }

        $blockedWords = $blockedWordsQuery->get();

        return view('blocked-words', [
            'blockedWords' => $blockedWords,
            'query' => $query
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), ['blocked-words' => 'required|max:255|unique:' . BlockedWord::class . ',content']);

        if ($validator->fails()) {
            return redirect(route('blocked-word.index'))->withErrors($validator->errors());
        }

        $blockedWord = new BlockedWord;
        $blockedWord->content = $request->input('blocked-words');
        $blockedWord->save();

        return redirect(route('blocked-word.index'));
    }

    public function delete(BlockedWord $blockedWord)
    {
        $blockedWord->delete();

        return redirect(route('blocked-word.index'));
    }
}
