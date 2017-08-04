@extends('layouts.main')

@section('title')
    БД -> Пошук на карті
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form class="form-horizontal" action="{{ route('objects.map') }}" method="POST">
                <div class="input-group">
                    <div class="input-group-btn">
                        <select class="btn btn-default dropdown-toggle" name="type" id="type">
                            <option value="num">Номер</option>
                            <option value="imei">IMEI</option>
                            <option value="imsi">IMSI</option>
                        </select>
                    </div>
                    <input type="text" class="form-control" name="inputValue" id="inputValue" maxlength="15">
                    <span class="input-group-btn">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </span>
                    <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        @if (count($errors) > 0)
            <br>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    @if(isset($locations) && $locations->count() > 0)
        <br>
        <div class="row">
            <div class="col-md-3">
                <table class="table">
                    <tr>
                        <th>Дата</th>
                        <th>На карті</th>
                    </tr>
                    @foreach($locations as $loc)
                        <tr>
                            <td>{{ $loc->date }}</td>
                            <td><a tabindex="0" class="btn btn-default btn-sm" role="button" data-toggle="popover" data-trigger="focus" title="IMSI, IMEI, (LON, LAT)" data-content="{{ $loc->imsi }}, {{ $loc->imei }}, ({{ $loc->ll['lon'] }}, {{ $loc->ll['lat'] }})" id="point{{ $loc->id }}"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col-md-9">
                <div id="map" class="map"></div>
            </div>
        </div>
    @else
    <br>
    <div class="row">
        <div class="col-md-12">
            <div id="map" class="map"></div>
        </div>
    </div>
    @endif
@endsection

@push('styles')
<style>
    .map {
        height: 600px;
        width: 100%;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/ol.js') }}">
@endpush

@push('scripts')
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    });
</script>

<script src="{{ asset('js/ol.js') }}"></script>
<script src="{{ asset('js/FileSaver.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.3/FileSaver.min.js"></script>
<script>
    @if(isset($locations) && $locations->count() > 0)
    //координаты для кнопок
            @foreach($locations as $loc)
    var point{{ $loc->id }} = ol.proj.fromLonLat([{{ $loc->ll['lon'] }}, {{ $loc->ll['lat'] }}]);
    @endforeach

    //объект со всеми точками
    var geojsonObject = {
        'type': 'FeatureCollection',
        'features': [
                @foreach($locations as $loc)
            {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates":  [{{ $loc->ll['lon'] }}, {{ $loc->ll['lat'] }}]
                },
                "properties": {
                    "name": "{{ $loc->rxtx['rx'] }}/{{ $loc->rxtx['tx'] }}"
                }
            },
            @endforeach
        ]};

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
        }),
        crossOrigin: 'anonymous'
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
        center: ol.proj.fromLonLat([{{ $locations->first()->ll['lon'] }}, {{ $locations->first()->ll['lat'] }}]),
        zoom: 14,
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

    @foreach($locations as $loc)
        onClick('point{{ $loc->id }}', function () {
        view.animate({
            center: point{{ $loc->id }},
            duration: 1000
        });
    });
    @endforeach

    //експортирование карты в png
    document.getElementById('export-png').addEventListener('click', function() {
        map.once('postcompose', function(event) {
            var canvas = event.context.canvas;
            if (navigator.msSaveBlob) {
                navigator.msSaveBlob(canvas.msToBlob(), 'map.png');
            } else {
                canvas.toBlob(function(blob) {
                    saveAs(blob, 'map.png');
                });
            }
        });
        map.renderSync();
    });
    @endif
</script>
@endpush