<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\CloudWatchSearchHistory;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $members = Member::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
        })
        ->oldest()
        ->paginate(4);

        return view('members.index', compact('members'));
    }

    public function liveSearch(Request $request): JsonResponse
    {
        $search = $request->get('search', '');

        if (!empty($search)) {
            CloudWatchSearchHistory::updateOrCreate(
                ['search_query' => $search],
                ['updated_at' => now()]
            );
        }

        $members = Member::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
        })
        ->oldest()
        ->get();

        return response()->json([
            'members' => $members
        ]);
    }

    public function getSearchMeta(Request $request): JsonResponse
    {
        $query = $request->get('query', '');

        $history = CloudWatchSearchHistory::orderBy('updated_at', 'desc')->take(5)->pluck('search_query')->toArray();

        $suggestions = [];
        if (!empty($query)) {
            $roles = ['Admin', 'Manager', 'Developer', 'User'];
            $suggestions = array_values(array_filter($roles, function ($role) use ($query) {
                return str_contains(strtolower($role), strtolower($query));
            }));
        }

        return response()->json([
            'history' => $history,
            'suggestions' => $suggestions
        ]);
    }

    public function clearSearchHistory(): JsonResponse
    {
        CloudWatchSearchHistory::truncate();
        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:members',
            'role' => 'required',
        ]);

        Member::create($request->all());

        return redirect()
            ->route('members.index')
            ->with('success', 'Member created successfully.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'role' => 'required',
        ]);

        $member->update($request->all());

        return redirect()
            ->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()
            ->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }
}