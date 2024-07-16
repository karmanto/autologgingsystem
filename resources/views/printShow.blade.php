<!-- resources/views/printShow.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
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
        <div class="container-contact100-form-btn">
            <div class="wrap-contact100-form-btn">
                <div class="contact100-form-bgbtn"></div>
                <form action="{{ route('comment') }}" method="GET">
                    <input type="hidden" name="set_date" value="{{ $set_date }}">
                    <button class="contact100-form-btn" type="submit">
                        <span>INSERT COMMENT</span>
                    </button>
                </form>
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
    const comment = @json($comment);

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
            const nickname = settings["name_list"].find(item => item.field === "cbc1").nickname;
            printString += showPerField(allLogs[key], key, nickname);
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

    if (comment) {
        printString += leftAlignString(`comment rev: ${comment["rev"]}`, 32, " ") + "\n";
        printString += leftAlignString(`by: ${comment["by"]}`, 32, " ") + "\n";
        printString += leftAlignString(`date comment: ${formatDate(comment["created_at"])}`, 32, " ") + "\n";
        printString += "--------------------------------\n";
        printString += adjustAlignSetting(`${comment["comment"]}`, 32, " ") + "\n";
        printString += "================================\n";
    }

    printString = printString.replace(/ /g, "&nbsp;")
                             .replace(/</g, "&lt;")
                             .replace(/>/g, "&gt;")
                             .replace(/\n/g, "<br>");

    document.getElementById("preview").innerHTML = printString;
});

function formatDate(dateString) {
    const date = new Date(dateString);

    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); 
    const year = date.getFullYear().toString().slice(-2);
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');

    return `${day}-${month}-${year} ${hours}:${minutes}`;
}

function showPerField(data, key, nickname){
    returnString = "";
    if (data["data"] && data["data"].length) {
        for (const key2 in data["data"]) {
            const date = new Date(data["data"][key2]["changed_at"]);
            const timePart = String(date.getHours()).padStart(2, '0') + ":" + String(date.getMinutes()).padStart(2, '0') + ":" + String(date.getSeconds()).padStart(2, '0');
            if (key2 == 0) {
                returnString += leftAlignString(nickname, 7, " ") + "|" + timePart + "|";
            } else if (key2 % 2 === 1) {
                returnString += timePart + "|";
                if (key2 < data["data"].length - 1) {
                    returnString += rightAlignString("", 6, " ") + "\n";
                }
            } else if (key2 % 2 === 0) {
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

function adjustAlignSetting(string, number, filler) {
    let lines = [];
    let words = string.split(' ');
    let line = '';

    for (let i = 0; i < words.length; i++) {
        let word = words[i];
        if (line.length + word.length <= number) {
            line += word + ' ';
        } else {
            lines.push(line.trim().padEnd(number, filler));
            line = word + ' ';
        }
    }
    
    if (line.length > 0) {
        lines.push(line.trim().padEnd(number, filler));
    }

    return lines.join("\n");
}


</script>
<script  src="js/AnyPrint.js" ></script>
@endsection