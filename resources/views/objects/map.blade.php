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

    var geojsonObject = {
        'type': 'FeatureCollection',
        'features': [{
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [34.540126666667, 49.59034]
            },
            "properties": {
                "name": "-49/-58"
            }
        }]
    };

    var styles = {
        'Point' : new ol.style.Style({
            image: new ol.style.Circle({
                fill: new ol.style.Fill({
                    color: 'rgba(255, 0, 0, 1)'
                }),
                radius: 7
            })
        })
    };

    var styleFunction = function(feature) {

        var text = new ol.style.Style({
            text :new ol.style.Text({
                text: feature.getProperties().name,
                font: '12px Calibri,sans-serif',
                weight:'Bold',
                fill: new ol.style.Fill({ color: '#000' }),
                stroke: new ol.style.Stroke({
                    color: '#D3D3D3', width: 10
                }),
                offsetY: 20,
                rotation: 0
            })
        });
        return [styles[feature.getGeometry().getType()],text];
    };

    var sourceLayer = new ol.layer.Tile({
        source: new ol.source.XYZ({
            url: 'http://localhost/maps/{z}/{x}/{y}.png.tile'
        })
    });

    var pointLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: (new ol.format.GeoJSON()).readFeatures(geojsonObject, {featureProjection: 'EPSG:3857'})
        }),
        style: styleFunction
    });

    var map = new ol.Map({
        target: 'map',
        layers: [sourceLayer, pointLayer],
        view: new ol.View({
            center: ol.proj.fromLonLat([34.540126666667, 49.59034]),
            zoom: 15,
            maxZoom: 19
        })
    });

</script>
@endpush
