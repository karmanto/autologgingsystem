<!-- resources/views/printShow.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-contact100">

	<div class="wrap-contact100">
        <span class="contact100-form-title fs-20" style="color: #ee2244;">{{ $settings['pt_name'] }}</span>
        <span class="contact100-form-title fs-39">LOGSHEET PREVIEW</span>
		<p id="preview" style="font-family: Monospace; width:100%; text-align: center;"></p>
        <div class="container-contact100-form-btn" id="printButton">
            <div class="wrap-contact100-form-btn">
                <div class="contact100-form-bgbtn"></div>
                <button class="contact100-form-btn" type="button" onclick="AnyPrint.Print('preview')">
                    <span>PRINT</span>
                </button>
            </div>
        </div>
	</div>
</div>
@endsection

@section('scripts')
<script>
    
document.addEventListener("DOMContentLoaded", function() {
    const settings = @json($settings);
    const allLogs = @json($allLogs);
    const startDate = @json($startDate);
    const endDate = @json($endDate);

    const ptName = settings["pt_name"];
    let printString = "";
    printString += centerAlignString(`${ptName}`, 32, " ") + "\n";
    printString += centerAlignString(`${startDate} >> ${endDate}`, 32, " ") + "\n";
    printString += "================================\n";
    printString += "NAME   |T-ON    |T_OFF   |RUN-HR\n";
    printString += "================================\n";

    const keys = Object.keys(allLogs);
    const totalKeys = keys.length;

    if (totalKeys > 0) {
        keys.forEach((key, index) => {
            printString += showPerField(allLogs[key], key);
            if (index < totalKeys - 1) {
                printString += "\n--------------------------------\n";
            } else {
                printString += "\n================================\n";
            }
        });
    } else {
        printString += centerAlignString(`no data`, 32, " ") + "\n";
        printString += "================================\n";
    }

    printString = printString.replace(/ /g, "&nbsp;")
                             .replace(/</g, "&lt;")
                             .replace(/>/g, "&gt;")
                             .replace(/\n/g, "<br>");

    document.getElementById("preview").innerHTML = printString;
});

function showPerField(data, key){
    returnString = "";
    if (data["data"] && data["data"].length) {
        for (const key2 in data["data"]) {
            if (key2 == 0) {
                const timePart = data["data"][key2]["changed_at"].split(' ')[1];
                returnString += leftAlignString(key, 7, " ") + "|" + timePart + "|";
            } else if (key2 % 2 === 1) {
                const timePart = data["data"][key2]["changed_at"].split(' ')[1];
                returnString += timePart + "|";
                if (key2 < data["data"].length - 1) {
                    returnString += rightAlignString("", 6, " ") + "\n";
                }
            } else if (key2 % 2 === 0) {
                const timePart = data["data"][key2]["changed_at"].split(' ')[1];
                returnString += leftAlignString("", 7, " ") + "|" + timePart + "|";
            }
        }
        returnString += rightAlignString(data["runHour"].toFixed(2), 6, " ");
    } else {
        returnString += leftAlignString(key, 7, " ") + "|        |        |     0";
    }
    return returnString;
}

function centerAlignString(string, length, filler) {
    while (string.length < length) {
        string = string.length % 2 === 0 ? filler + string : string + filler;
    }
    return string;
}

function leftAlignString(string, number, filler){
	while(string.length<number){
		string = string + filler;
	};
	return string;
}

function rightAlignString(string, number, filler){
	while(string.length<number){
		string = filler + string;
	};
	return string;
}

</script>
<script  src="js/AnyPrint.js" ></script>
@endsection