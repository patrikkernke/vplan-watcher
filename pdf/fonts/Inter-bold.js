﻿const { jsPDF } = require("jspdf");

var callAddFont = function () {
this.addFileToVFS('Inter-bold.ttf', font);
this.addFont('Inter-bold.ttf', 'Inter', 'bold');
};
jsPDF.API.events.push(['addFonts', callAddFont])