var AnyPrint = (function () {
    function self() {
    }

    if (document.querySelectorAll('iframe[name="print_frame"]').length === 0) {
        var printElement = document.createElement('iframe');
        printElement.setAttribute('name', 'print_frame');
        printElement.setAttribute('width', 0);
        printElement.setAttribute('height', 0);
        printElement.setAttribute('frameborder', 0);
        printElement.setAttribute('id', 'frameToPrint');
        printElement.setAttribute('src', 'about:blank');
        document.body.appendChild(printElement);
    }

    self.prototype.options = {};
    self.prototype.defaultOptions = {
        removeHeaderAndFooter: false,
        appendDate: false,
        title: document.title
    };

    self.prototype.loadOptions = function (options) {

        self.prototype.options["removeHeaderAndFooter"] = options["removeHeaderAndFooter"] || self.prototype.defaultOptions["removeHeaderAndFooter"];
        self.prototype.options["appendDate"] = options["appendDate"] || self.prototype.defaultOptions["appendDate"];
        self.prototype.options["title"] = options["title"] || self.prototype.defaultOptions["title"];


    };


    self.prototype.loadOptions({})

    self.prototype.isPrinting = false;


    self.prototype.elementBody = "";

    self.prototype.createNewEvent = function (eventName) {
        var event;
        if (typeof (Event) === 'function') {
            event = new Event(eventName);
        } else {
            event = document.createEvent('Event');
            event.initEvent(eventName, true, true);
        }
        return event;
    }

    self.prototype.prepareBody = function () {

        if (window.frames["print_frame"].document.body.innerHTML) {
            self.prototype.elementBody = "";
            window.frames["print_frame"].document.body.innerHTML = "";
        }


        if (self.prototype.options['appendDate'] === true) {
            self.prototype.options['title'] += ' ' + new Date().toLocaleDateString;
        }

        var styleLinks = document.querySelectorAll('link');

        for (var i = 0; i < styleLinks.length; i++) {
            self.prototype.appendCssToFrame(styleLinks[i]);
        }

        if (self.prototype.options["removeHeaderAndFooter"] === true) {

            var cssRules = 'html, body {margin: 0 auto; } @page { size: auto; margin: 0 auto; }';

            var styleElement = document.createElement('style');

            styleElement.appendChild(document.createTextNode(cssRules));

            window.frames["print_frame"].document.head.appendChild(styleElement);
        }



    }

    self.prototype.setupVisibilities = function () {
        var frame = document.getElementById('frameToPrint');
        var c = frame.contentDocument || frame.contentWindow.document;

        var hiddens = c.getElementById("htmlToPrint").querySelectorAll('[showOnPrint="hidden"]');

        for (var i = 0; i < hiddens.length; i++) {
            hiddens[i].parentNode.removeChild(hiddens[i]);
        }

        var visibles = c.getElementById("htmlToPrint").querySelectorAll('[showOnPrint="visible"]');


        for (var i = 0; i < visibles.length; i++) {
            visibles[i].classList.remove('hidden');
            visibles[i].style.display = visibles[i].style.display === "none" ? "" : visibles[i].style.display;
        }

        window.frames["print_frame"].document.title = self.prototype.options['title'];
        window.frames["print_frame"].window.focus();
    }

    self.config = function (options) {
        self.prototype.loadOptions(options)
    }

    self.Print = function (elementId) {

        if (self.prototype.isPrinting)
            return;

        self.prototype.isPrinting = true;
        var originalTitle = document.title;

        self.prototype.prepareBody();


        var logo = document.querySelectorAll('.logo').length < 1
            ? ""
            : '<div style="width: 100%">' + document.querySelector('.logo').outerHTML + '</div>';


        if (elementId) {
            self.prototype.elementBody = logo + document.getElementById(elementId).outerHTML;
        }
        else {
            var markedToPrint = document.querySelectorAll('[anyPrint]');

            for (var i = 0; i < markedToPrint.length; i++) {
                var pn = markedToPrint[i].offsetParent;
                var parentIsPrintable = false;
                while (pn && !parentIsPrintable) {
                    parentIsPrintable = pn.hasAttribute("anyPrint")
                    pn = pn.offsetParent;
                }

                if (!parentIsPrintable) {
                    self.prototype.elementBody += markedToPrint[i].outerHTML
                }

            }
        }



        window.frames["print_frame"].document.body.innerHTML = self.prototype.elementBody;
        window.frames["print_frame"].document.childNodes[0].id = "htmlToPrint";

        self.prototype.setupVisibilities();


        var printCount = 0;
        var printerInterval = setInterval(function () {
            printCount++;
            if (printCount > 2) {
                self.prototype.isPrinting = false;
                clearInterval(printerInterval);
                if (!self.prototype.firstError) {
                    self.prototype.firstError = true;
                    return self.Print(elementId);
                }
                else {
                    throw new Error("Print Failed");
                }
            }
            if (window.frames["print_frame"].document.body.innerHTML.length > 0) {
                document.dispatchEvent(self.prototype.createNewEvent('AnyPrinted'));
                window.frames["print_frame"].window.print();
                document.title = originalTitle;
                self.prototype.isPrinting = false;
                clearInterval(printerInterval);
                return;
            }
        }, 250);

    };
    self.prototype.appendCssToFrame = function (cssElement) {
        var linkElement = document.createElement('link');
        linkElement.setAttribute('rel', cssElement.getAttribute('rel'));
        linkElement.setAttribute('type', 'text/css');
        linkElement.setAttribute('href', cssElement.getAttribute('href'));

        window.frames["print_frame"].document.head.appendChild(linkElement);
        return linkElement.outerHTML;
    };
    self.prototype.firstError = false;

    return self;
}());


