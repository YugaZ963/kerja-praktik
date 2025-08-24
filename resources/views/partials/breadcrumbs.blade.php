@if(isset($breadcrumbs) && count($breadcrumbs) > 1)
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            @if($breadcrumb['active'])
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $breadcrumb['title'] }}
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                        {{ $breadcrumb['title'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>

{{-- Structured Data for Breadcrumbs --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        @foreach($breadcrumbs as $index => $breadcrumb)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ $breadcrumb['title'] }}",
            "item": "{{ $breadcrumb['url'] }}"
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif