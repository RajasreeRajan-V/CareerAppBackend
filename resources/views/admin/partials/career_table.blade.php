@forelse ($careerNodes as $career)
    <tr>
       <td class="align-middle" style="position: relative; overflow: hidden;">
            @if ($career->newgen_course)
                <span class="table-newgen-label"> NEWGEN</span>
            @endif
            @if ($career->video)
                {{-- rest of video thumbnail code --}}
                @php $videoId = $career->video; @endphp
                <div class="position-relative video-thumbnail"
                    style="width:120px;height:75px;cursor:pointer;overflow:hidden;border-radius:6px;"
                    data-video-id="{{ $videoId }}" data-video-title="{{ $career->title }}">
                    <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                        class="w-100 h-100" style="object-fit:cover;">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i class="fas fa-play-circle fa-3x text-white"
                            style="text-shadow:0 0 10px rgba(0,0,0,0.8);opacity:0.9;"></i>
                    </div>
                </div>
            @elseif ($career->thumbnail)
                <img src="{{ asset('storage/' . $career->thumbnail) }}"
                    style="width:120px;height:75px;object-fit:cover;border-radius:6px;">
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        <td class="align-middle">
            @if ($search)
                {!! preg_replace('/('. preg_quote($search, '/') .')/i', '<mark style="background-color: #FFEB3B; padding: 2px 4px; border-radius: 3px;">$1</mark>', e($career->title)) !!}
            @else
                {{ $career->title }}
            @endif
        </td>

        <td class="align-middle">
            @php
                $subjects = $career->subjects;
                if (is_string($subjects)) {
                    $decoded = json_decode($subjects, true);
                    $subjects =
                        json_last_error() === JSON_ERROR_NONE
                            ? $decoded
                            : explode(',', $subjects);
                }
            @endphp
            @if (is_array($subjects) && count($subjects))
                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                    @foreach ($subjects as $subject)
                        <li>
                            @if ($search)
                                {!! preg_replace('/('. preg_quote($search, '/') .')/i', '<mark style="background-color: #FFEB3B; padding: 2px 4px; border-radius: 3px;">$1</mark>', e(trim($subject))) !!}
                            @else
                                {{ trim($subject) }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                -
            @endif
        </td>

        <td class="align-middle">
            @php
                $options = $career->career_options;
                if (is_string($options)) {
                    $decoded = json_decode($options, true);
                    $options =
                        json_last_error() === JSON_ERROR_NONE
                            ? $decoded
                            : explode(',', $options);
                }
            @endphp
            @if (is_array($options) && count($options))
                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                    @foreach ($options as $option)
                        <li class="mb-1">
                            @if ($search)
                                {!! preg_replace('/('. preg_quote($search, '/') .')/i', '<mark style="background-color: #FFEB3B; padding: 2px 4px; border-radius: 3px;">$1</mark>', e(trim($option))) !!}
                            @else
                                {{ trim($option) }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                -
            @endif
        </td>

        <td class="align-middle">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary editCareerBtn"
                    data-bs-toggle="modal" data-bs-target="#editCareerModal"
                    data-id="{{ $career->id }}" data-title="{{ e($career->title) }}"
                    data-description="{{ e($career->description) }}"
                    data-specialization="{{ e($career->specialization) }}"
                    data-newgen-course="{{ $career->newgen_course ? '1' : '0' }}"
                    data-thumbnail="{{ $career->thumbnail ? asset('storage/' . $career->thumbnail) : '' }}"
                    title="Edit">
                    <i class="fas fa-edit"></i>
                </button>

                <form action="{{ route('admin.career_nodes.destroy', $career->id) }}"
                    method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to delete this career?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-4x mb-3 d-block opacity-50"></i>
            <p class="mb-0">No careers found</p>
        </td>
    </tr>
@endforelse
