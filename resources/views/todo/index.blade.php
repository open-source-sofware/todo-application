<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        @if(session('message'))
                            <div class="bg-teal-100  border-l-4  border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                                <p class="font-bold">Message:</p>
                                <p class="text-sm">{{session("message")}}</p>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                                <p class="font-bold">Sorry:</p>

                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- add todos --}}
                        <div class="mt-5">
                            <form action="{{route("todos.store")}}" method="post">
                                @csrf
                                <textarea rows="4"
                                          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                                          focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                           dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                          placeholder="Add new todo here.."
                                          name="description"></textarea>

                                <button
                                    class="mt-2 bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                    Add Todo
                                </button>
                            </form>
                        </div>
                        {{-- filters --}}
                        <div class="mt-4 flex flex-col items-center  ">
                            <form action="{{route("todos.index")}}" id="filters">
                                @csrf

                                <input
                                    name="keyword"
                                    class="shadow border w-full rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none "
                                    type="text"
                                    placeholder="Search todos"
                                    value="{{ request('keyword') }}"
                                    autofocus>

                                <div class=" mt-2">

                                    <input
                                        value="1"
                                        type="checkbox"
                                        class="inline"
                                        name="finished"
                                        {{request()->finished ? "checked" : ""}}
                                        onclick="document.getElementById('filters').submit()"
                                    >
                                    Finished
                                    </input>

                                    <input
                                        value="1"
                                        type="checkbox"
                                        class="inline"
                                        name="unfinished"
                                        {{request()->unfinished ? "checked" : ""}}
                                        onclick="document.getElementById('filters').submit()"
                                    >
                                    Unfinished
                                    </input>

                                    <input
                                        value="1"
                                        type="checkbox"
                                        class="inline"
                                        name="mytodos"
                                        {{request()->mytodos ? "checked" : ""}}
                                        onclick="document.getElementById('filters').submit()"
                                    >
                                    My Todos
                                    </input>
                                </div>
                            </form>
                            <label class="mt-4 block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">

                                @if($todos->total() > 0)
                                    Total Result: {{$todos->total()}}
                                @else
                                    No result found
                                @endif

                                <a class="underline text-cyan-700" href="{{route('todos.index')}}">Clear</a>
                            </label>
                        </div>

                        {{-- todos list  --}}
                        <div>
                            <ul>
                                @foreach($todos as $todo)
                                    <li class="p-2">
{{--                                        {{$todo->status->value->value}}--}}
                                        <form action="{{route("todos.update", $todo->id)}}" method="post" class="inline"
                                              id="form-check-{{$todo->id}}">

                                            @csrf

                                            @method("PATCH")

                                            <input type="hidden" name="fragment" value="input-check-{{$todo->id}}"/>

                                            <input
                                                onclick="document.getElementById('form-check-{{$todo->id}}').submit()"
                                                {{$todo->status->value == 1 ? "checked" : ""}}
                                                value="{{$todo->status->value}}"
                                                id="input-check-{{$todo->id}}"
                                                name="status"
                                                type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500
                                                dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2
                                                dark:bg-gray-700 dark:border-gray-600">
                                        </form>

                                        <label class="{{$todo->status->value === 1 ? "line-through" : ""}}">
                                            #{{ $todo->id}} - {{ $todo->description}}
                                        </label>

                                        <form action="{{route("todos.destroy", $todo->id)}}" method="post"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this todo list #{{$todo->id}}?')">
                                            @csrf
                                            @method("DELETE")

                                            <input type="hidden" name="fragment" value="input-delete-{{$todo->id}}"/>

                                            <button id="input-delete-{{$todo->id}}"
                                                    type="submit"
                                                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1
                                                    px-2 border border-gray-400 rounded shadow">
                                                        <i title="Delete this todo"
                                                       class="fa fa-trash text-orange-600 cursor-pointer"
                                                       aria-hidden="true"></i>
                                            </button>
                                        </form>

                                        <div>
                                            <small>
                                                <b>Posted By:</b>
                                                {{$todo?->owner?->name}}
                                                <em>
                                                    {{$todo->created_at->format('Y-m-d h:i a')}}
                                                    ({{$todo->created_at->ago()}})
                                                </em>
                                            </small>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div>
                                {{ $todos->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
