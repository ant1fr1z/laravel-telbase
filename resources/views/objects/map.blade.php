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

    <div class="row">
        <div class="col-md-12">
            <div id="map" class="map"></div>
            <button id="point1">1</button>
            <button id="point2">2</button>
            <button id="point3">3</button>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ol.js') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/ol.js') }}"></script>
<script>
    //координаты для кнопок
    var point1 = ol.proj.fromLonLat([34.540126666667, 49.59034]);
    var point2 = ol.proj.fromLonLat([33.540126666667, 42.59034]);
    var point3 = ol.proj.fromLonLat([36.540126666667, 23.59034]);

    //объект со всеми точками
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
        }, {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [33.540126666667, 42.59034]
            },
            "properties": {
                "name": "-12/-21"
            }
        }, {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [36.540126666667, 23.59034]
            },
            "properties": {
                "name": "-70/-110"
            }
        }]
    };

    //стиль точки
    var styles = {
        'Point' : new ol.style.Style({
            image: new ol.style.Circle({
                fill: new ol.style.Fill({
                    color: '#ff0900'
                }),
                stroke: new ol.style.Stroke({
                    color: '#000000', width: 2
                }),
                radius: 7
            })
        })
    };

    //функция построения общего стиля с подстановкой названия точки
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

    //слой карты
    var sourceLayer = new ol.layer.Tile({
        source: new ol.source.XYZ({
            url: 'http://localhost/maps/{z}/{x}/{y}.png.tile'
        })
    });

    //слой точек
    var pointLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: (new ol.format.GeoJSON()).readFeatures(geojsonObject, {featureProjection: 'EPSG:3857'})
        }),
        style: styleFunction
    });

    //начальный вид
    var view = new ol.View({
        center: ol.proj.fromLonLat([34.540126666667, 49.59034]),
        zoom: 15,
        maxZoom: 19
    });

    //строим карту
    var map = new ol.Map({
        target: 'map',
        layers: [sourceLayer, pointLayer],
        loadTilesWhileAnimating: true,
        view: view
    });

    //обработка кнопок
    function onClick(id, callback) {
        document.getElementById(id).addEventListener('click', callback);
    };

    onClick('point1', function () {
        view.animate({
            center: point1,
            duration: 1000
        });
    });
    onClick('point2', function () {
        view.animate({
            center: point2,
            duration: 1000
        });
    });
    onClick('point3', function () {
        view.animate({
            center: point3,
            duration: 1000
        });
    });

</script>
@endpush
