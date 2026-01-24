@extends('layouts.dashboard')
@section('title', 'Users')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Users</h1>
            <p class="text-sm text-gray-500">Overview / Users</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-4 flex items-center gap-3 justify-between">
            <form class="flex items-center gap-3 w-full">
                <div class="flex-1">
                    <input name="search" value="{{ request('search') }}" class="w-full border rounded-lg px-3 py-2"
                        placeholder="Search..." />
                </div>

                <select name="type" class="border rounded-lg px-3 py-2">
                    <option value="">All Types</option>
                    <option value="customer" @selected(request('type') === 'customer')>customer</option>
                    <option value="provider" @selected(request('type') === 'provider')>provider</option>
                    <option value="employee" @selected(request('type') === 'employee')>employee</option>
                </select>

                <select name="per_page" class="border rounded-lg px-3 py-2">
                    @foreach([5, 10, 20, 50] as $n)
                        <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>Show {{ $n }}</option>
                    @endforeach
                </select>

                <button class="px-4 py-2 rounded-lg bg-gray-900 text-white">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Name</th>
                        <th class="text-left px-4 py-3">Email</th>
                        <th class="text-left px-4 py-3">Type</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Created At</th>
                        <th class="text-right px-4 py-3">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $u->name }}</td>
                            <td class="px-4 py-3">{{ $u->email }}</td>
                            <td class="px-4 py-3">{{ $u->type }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-green-700 bg-green-50 border border-green-200">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Active
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ optional($u->created_at)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a class="inline-flex items-center justify-center w-9 h-9 rounded-lg border hover:bg-gray-50"
                                    href="{{ route('dashboard.users.edit', $u->id) }}">✏️</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
