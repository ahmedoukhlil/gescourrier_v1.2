<!-- resources/views/courriers/includes/annotations.blade.php -->

@if($courrier->annotations->count() > 0)
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-lg font-semibold mb-4">Annotations</h2>
    
    <div class="space-y-4">
        @foreach($courrier->annotations as $annotation)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="font-medium">{{ $annotation->annotator->name }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $annotation->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if(Auth::id() === $annotation->annotated_by || Auth::user()->isAdmin())
                        <form action="{{ route('courriers.annotations.destroy', $annotation) }}" method="POST" class="ml-2" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annotation?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
                <div class="whitespace-pre-line text-gray-700">{{ $annotation->annotation }}</div>
                
                @if($annotation->shares->count() > 0 && Auth::user()->canAnnotateCourriers())
                    <div class="mt-2 pt-2 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-500">Partagé avec:</p>
                        <div class="flex flex-wrap mt-1 gap-1">
                            @foreach($annotation->shares as $share)
                                <span class="px-2 py-1 text-xs rounded-full {{ $share->is_read ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $share->recipient->name }}
                                    @if($share->is_read)
                                        <span class="ml-1">(Lu)</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@elseif(Auth::user()->canAnnotateCourriers())
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <div class="text-center py-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900">Aucune annotation</h3>
        <p class="mt-1 text-sm text-gray-500">
            Ce courrier n'a pas encore été annoté.
        </p>
        <div class="mt-4">
            <a href="{{ route('courriers.annotations.create', $courrier) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                Ajouter une annotation
            </a>
        </div>
    </div>
</div>
@endif