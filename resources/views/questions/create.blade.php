<x-forum.layouts.app>

@if(session('success'))
    <div class="bg-green-600 text-white px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Crear Nueva Pregunta</h1>

    <form action="{{ route('questions.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium text-gray-300">Título</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            @error('title')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-300">Categoría</label>
            <select name="category_id" id="category_id" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Selecciona una categoría</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="content" class="block text-sm font-medium text-gray-300">Contenido</label>
            <textarea name="content" id="content" rows="8" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm bg-gray-800 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('content') }}</textarea>
            @error('content')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-500">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Crear Pregunta</button>
        </div>
    </form>
</div>

</x-forum.layouts.app>