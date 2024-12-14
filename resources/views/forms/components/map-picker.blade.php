<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="mapPicker({
            state: $wire.entangle('{{ $getStatePath() }}'), // Pastikan state terhubung dengan Livewire
            defaultLatitude: {{ $getDefaultLatitude() ?? -6.8883 }},
            defaultLongitude: {{ $getDefaultLongitude() ?? 109.6784 }},
        })" wire:ignore style="height: 400px; width: 100%; margin-top: 10px; border: 1px solid #ccc;">
        <div id="map" style="height: 100%; width: 100%;"></div>
    </div>
</x-dynamic-component>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mapPicker', ({
            state,
            defaultLatitude,
            defaultLongitude
        }) => ({
            map: null,
            marker: null,
            state,

            init() {
                // Validasi state
                if (!this.state) {
                    console.error('Livewire state is not defined.');
                    return;
                }

                // Inisialisasi peta
                this.map = L.map(this.$el.querySelector('#map')).setView([defaultLatitude,
                    defaultLongitude
                ], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(this.map);

                // Tambahkan marker
                this.marker = L.marker([defaultLatitude, defaultLongitude], {
                    draggable: true,
                }).addTo(this.map);

                // Update Livewire state dan input DOM saat marker di-drag
                this.marker.on('dragend', (e) => {
                    const {
                        lat,
                        lng
                    } = e.target.getLatLng();

                    // Perbarui Livewire state
                    if (this.state) {
                        this.state.latitude = lat.toFixed(8);
                        this.state.longitude = lng.toFixed(8);
                    }

                    // Perbarui nilai input secara langsung menggunakan ID
                    const latitudeInput = document.getElementById('{{ $getId() }}-latitude');
                    const longitudeInput = document.getElementById('{{ $getId() }}-longitude');

                    if (latitudeInput) latitudeInput.value = lat.toFixed(8);
                    if (longitudeInput) longitudeInput.value = lng.toFixed(8);
                });

                // Update marker saat Livewire state berubah
                this.$watch('state', (newState) => {
                    if (newState?.latitude && newState?.longitude) {
                        const {
                            latitude,
                            longitude
                        } = newState;
                        this.marker.setLatLng([latitude, longitude]);
                        this.map.setView([latitude, longitude]);
                    }
                });
            },
        }));
    });
</script>