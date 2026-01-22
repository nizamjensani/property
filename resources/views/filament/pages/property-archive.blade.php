<x-filament-panels::page>
    <div class="pa-wrapper">
        {{-- Filters --}}
        <div class="pa-filters">
            {{ $this->getForm('form') }}

            <div class="pa-filters-actions">
                <x-filament::button color="gray" wire:click="resetFilters">
                    Reset
                </x-filament::button>
                <x-filament::button color="primary" wire:click="applyFilters">
                    Apply
                </x-filament::button>
            </div>
        </div>

        @php
        $properties = $this->listings;
        @endphp

        {{-- Results --}}
        <div class="pa-grid">
            @if ($properties->count() === 0)
            <div class="pa-empty">
                No listings found.
            </div>
            @else
            @foreach ($properties as $property)
            @php
            $cover = $property->getFirstMediaUrl('cover') ?: null;

            $price = $property->listing_type === 'rent'
            ? ($property->currency . ' ' . number_format((int) ($property->monthly_rent ?? 0)) . ' /mo')
            : ($property->currency . ' ' . number_format((int) ($property->price ?? 0)));
            @endphp

            <div class="pa-card">
                {{-- image --}}
                <div class="pa-card-image">
                    @if ($cover)
                    <img src="{{ $cover }}" alt="">
                    @else
                    <div class="pa-card-image-empty">No image</div>
                    @endif
                </div>

                {{-- body --}}
                <div class="pa-card-body">
                    <div class="pa-title">
                        {{ $property->title }}
                    </div>

                    <div class="pa-price">
                        {{ $price }}
                    </div>

                    <div class="pa-specs">
                        <div>{{ $property->bedrooms ?? '-' }} Beds</div>
                        <div>{{ $property->bathrooms ?? '-' }} Baths</div>
                    </div>

                    <div class="pa-type">
                        {{ $property->propertyType?->name ?? '—' }}
                    </div>

                    {{-- agent --}}
                    <div class="pa-agent">
                        <div class="pa-agent-label">AGENT</div>
                        <div class="pa-agent-name">
                            {{ $property->user?->name ?? '—' }}
                        </div>
                        <div class="pa-agent-phone">
                            {{ $property->user?->phone_number ?? '—' }}
                        </div>

                        <a
                            href="{{ url('/admin/properties/' . $property->id) }}"
                            class="pa-agent-link"
                        >
                            View Property
                        </a>

                    </div>
                    
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <div class="pa-pagination">
            {{ $properties->links() }}
        </div>
    </div>
</x-filament-panels::page>