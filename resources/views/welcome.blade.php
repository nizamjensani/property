<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full flex justify-between items-center lg:max-w-7xl sm:max-w-3xl max-w-[450px] text-sm mb-6 not-has-[nav]:hidden lg:px-8 px-6">
        <div>
            <h1 class="text-base font-semibold">PORTAL HARTANAH KELANTAN</h1>
        </div>
        @if (Route::has('filament.admin.auth.login'))
        <nav class="flex items-center justify-end gap-4">
            @auth
            <a
                href="{{ route('filament.admin.pages.dashboard') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                Dashboard
            </a>
            @else
            <a
                href="{{ route('filament.admin.auth.login') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                Log in
            </a>

            @endauth
        </nav>
        @endif
    </header>
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex max-w-[450px] w-full flex-col-reverse sm:max-w-3xl lg:max-w-7xl lg:flex-row">
            @php
            // Example: $filters = request()->all();
            @endphp
            {{-- Filters --}}
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                {{-- Filters Box --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-sm">
                    <form method="GET" action="{{ route('properties.index') }}" class="space-y-5">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2 lg:grid-cols-4">
                            {{-- Search --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ request('q') }}"
                                    placeholder="Title"
                                    class="w-full text-sm rounded-md py-2 px-3 border border-gray-300 shadow shadow-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            {{-- Listing Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Listing Type</label>
                                <select
                                    name="listing_type"
                                    class="w-full rounded-md text-sm border p-2 shadow shadow-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select an option</option>
                                    <option value="sale" @selected(request('listing_type')==='sale' )>Sale</option>
                                    <option value="rent" @selected(request('listing_type')==='rent' )>Rent</option>
                                </select>
                            </div>

                            {{-- Property Type (MULTI) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                                <select
                                    name="property_type_ids[]"
                                    multiple
                                    class="js-select2-multi w-full"
                                    data-placeholder="Select an option">
                                    @foreach($propertyTypes as $id => $name)
                                    <option value="{{ $id }}"
                                        @selected(collect(request('property_type_ids', []))->contains((string)$id))
                                        >
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- State --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                <select
                                    id="state_id"
                                    name="state_id"
                                    class="w-full rounded-md text-sm border p-2 shadow shadow-gray-100 border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select an option</option>
                                    @foreach($states as $id => $name)
                                    <option value="{{ $id }}" @selected((string)request('state_id')===(string)$id)>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- City / Mukim (MULTI) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City / Mukim</label>

                                <select
                                    id="city_ids"
                                    name="city_ids[]"
                                    multiple
                                    disabled
                                    class="js-select2-multi w-full"
                                    data-placeholder="Select city">
                                    {{-- Start empty; we will fill via JS after state selected --}}
                                </select>

                                {{-- Optional: small helper text --}}
                                <p class="mt-1 text-xs text-gray-500">Select state first.</p>
                            </div>


                            {{-- Min Price --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                                <input
                                    type="number"
                                    name="min_price"
                                    value="{{ request('min_price') }}"
                                    class="w-full text-sm rounded-md py-2 px-3 border border-gray-300 shadow shadow-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="" />
                            </div>

                            {{-- Max Price --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                                <input
                                    type="number"
                                    name="max_price"
                                    value="{{ request('max_price') }}"
                                    class="w-full  text-sm rounded-md py-2 px-3 border border-gray-300 shadow shadow-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="" />
                            </div>

                            {{-- Bedrooms (min) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms (min)</label>
                                <select
                                    name="bedrooms_min"
                                    class="w-full  text-sm rounded-md py-2 px-3 border border-gray-300 shadow shadow-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select an option</option>
                                    @for($i=1; $i<=10; $i++)
                                        <option value="{{ $i }}" @selected((string)request('bedrooms_min')===(string)$i)>{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>

                            {{-- Bathrooms (min) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms (min)</label>
                                <select
                                    name="bathrooms_min"
                                    class="w-full text-sm rounded-md py-2 px-3 border border-gray-300 shadow shadow-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select an option</option>
                                    @for($i=1; $i<=10; $i++)
                                        <option value="{{ $i }}" @selected((string)request('bathrooms_min')===(string)$i)>{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>

                            {{-- Furnishing --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Furnishing</label>
                                <select
                                    name="furnishing"
                                    class="w-full rounded-md text-sm border p-2 shadow shadow-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select an option</option>
                                    <option value="unfurnished" @selected(request('furnishing')==='unfurnished' )>Unfurnished</option>
                                    <option value="partial" @selected(request('furnishing')==='partial' )>Partially Furnished</option>
                                    <option value="fully" @selected(request('furnishing')==='fully' )>Fully Furnished</option>
                                </select>
                            </div>

                            {{-- Sort --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sort</label>
                                <select
                                    name="sort"
                                    class="w-full rounded-md text-sm border p-2 shadow shadow-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="latest" @selected(request('sort','latest')==='latest' )>Latest</option>
                                    <option value="price_asc" @selected(request('sort')==='price_asc' )>Price (Low to High)</option>
                                    <option value="price_desc" @selected(request('sort')==='price_desc' )>Price (High to Low)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <a
                                href="{{ route('properties.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                Reset
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-2.5 text-sm font-semibold text-gray-900 hover:bg-amber-500">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Listing --}}
                <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse($properties as $property)
                    @php
                    $coverUrl = $property->getFirstMediaUrl('cover') ?: asset('images/placeholder-property.jpg');
                    @endphp

                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                        <div class="aspect-[4/3] w-full bg-gray-100">
                            <img
                                src="{{ $coverUrl }}"
                                alt="{{ $property->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy" />
                        </div>

                        <div class="p-5">
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $property->title }}
                            </div>

                            <div class="mt-2 text-xl font-extrabold text-gray-900">
                                MYR {{ number_format($property->price ?? 0) }}
                            </div>

                            <div class="mt-3 flex items-center gap-6 text-sm text-gray-600">
                                <div>{{ $property->bedrooms ?? '-' }} Beds</div>
                                <div>{{ $property->bathrooms ?? '-' }} Baths</div>
                            </div>

                            <div class="mt-4">
                                <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-semibold text-gray-700">
                                    {{ $property->propertyType?->name ?? '—' }}
                                </span>
                            </div>


                        </div>
                    </div>
                    @empty
                    <div class="col-span-full rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-600">
                        No properties found.
                    </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $properties->withQueryString()->links() }}
                </div>
            </div>

        </main>
    </div>

    @if (Route::has('filament.admin.auth.login'))
    <div class="h-14.5 hidden lg:block"></div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const $state = $("#state_id");
            const $city = $("#city_ids");

            // Request-selected city ids (for reload)
            const preselected = @json((array) request('city_ids', []));

            // Init Select2 for city
            $city.select2({
                width: "100%",
                closeOnSelect: false,
                placeholder: $city.data("placeholder") || "Select city",
            });

            function setCityDisabled(disabled) {
                $city.prop("disabled", disabled);
                // Select2 needs a change to reflect disabled state
                $city.trigger("change.select2");
            }

            function resetCityOptions() {
                $city.empty(); // remove all options
                // keep placeholder behavior: Select2 placeholder handles this for multi
                $city.trigger("change");
            }

            async function loadCities(stateId, selectedIds = []) {
                resetCityOptions();

                if (!stateId) {
                    setCityDisabled(true);
                    return;
                }

                setCityDisabled(true);

                try {
                    const res = await fetch(`{{ route('ajax.cities') }}?state_id=${encodeURIComponent(stateId)}`, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    });

                    if (!res.ok) throw new Error("Failed to load cities");

                    const data = await res.json(); // { "1":"City A", "2":"City B" }

                    // Populate options
                    Object.entries(data).forEach(([id, name]) => {
                        const opt = new Option(name, id, false, selectedIds.includes(String(id)));
                        $city.append(opt);
                    });

                    setCityDisabled(false);
                    $city.trigger("change"); // refresh Select2 UI
                } catch (e) {
                    resetCityOptions();
                    setCityDisabled(true);
                    // Optional: show toast / alert
                    // alert("Unable to load cities. Please try again.");
                    console.error(e);
                }
            }

            // When state changes
            $state.on("change", function() {
                loadCities(this.value, []); // clear selected when user changes state
            });

            // On page load:
            const initialState = $state.val();
            if (initialState) {
                loadCities(initialState, preselected);
            } else {
                setCityDisabled(true);
            }
        });
    </script>
</body>

</html>