@extends('layouts.main')

@section('title')
    БД -> Карта
@endsection

@section('content')
    <style>
        .map {
            height: 600px;
            width: 100%;
        }
    </style>

    <script src="https://openlayers.org/en/v4.2.0/build/ol.js" type="text/javascript"></script>
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList"></script>
    <div class="row">
        <div class="col-md-12">
            <div id="map" class="map"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.XYZ({
                    url: "maps/{z}/{x}/{y}.pbf"
                })
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([37.41, 8.82]),
            zoom: 4
        })
    });
</script>
@endpush
