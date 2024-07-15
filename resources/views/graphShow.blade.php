<!-- resources/views/printShow.blade.php -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('visavail/visavail/css/visavail.css') }}">
@endsection

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100" style="padding-right: 10px; padding-left: 10px; max-width:1000px; width:100%;">
        <span class="contact100-form-title fs-20" style="color: #ee2244;">{{ $settings['pt_name'] }}</span>
        <span class="contact100-form-title fs-39">GRAFIK PREVIEW</span>
        <p id="example"></p>
    </div>
</div>
@endsection

@section('scripts')
<script src="visavail/visavail/js/visavail.js"></script>
<script src="visavail/vendors/d3/d3.min.js"></script>
<script src="contactform/vendor/daterangepicker/moment.min.js"></script>
<script>
    
document.addEventListener("DOMContentLoaded", function() {
    const settings = @json($settings);
    const changeLogs = @json($changeLogs);

    const wrapWidth = document.querySelector('.wrap-contact100').offsetWidth;
    var chart = visavailChart().width(wrapWidth - 80);

    var dataset = [];
    const keys = Object.keys(changeLogs);
    const totalKeys = keys.length;

    if (totalKeys > 0) {
        keys.forEach((key, index) => {
            dataset.push(convertData(changeLogs[key], key));
        });
    }
    
    d3.select("#example")
            .datum(dataset)
            .call(chart)
});

function convertData(data, field){
    let dataResult = {
        "measure": field,
        "interval_s": 0,
        "data": [],
    };

    if (data && Array.isArray(data) && data.length > 0) {
        for (const key in data) {
            const dataCurrent = [];
            const dateConvert = formatDate(data[key]["created_at"])
            dataCurrent.push(dateConvert);
            dataCurrent.push(data[key]["value"]);
            dataResult["data"].push(dataCurrent);
        }
    } 

    return dataResult;
}

function formatDate(dateString) {
    const date = new Date(dateString);

    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); 
    const year = date.getFullYear().toString();
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

</script>
@endsection
