const PdfDocument = require("../pdf-designer/PdfDocument");
const Text = require('../pdf-designer/Element/Text')
const StackVertical = require("../pdf-designer/Element/StackVertical");

class TestPlan extends PdfDocument {

    content(data, pages) {
        return [
            Text.create('Das ist ein langer Satz. Er könnte auch länger gehen.').maxWidth(50),
        ]
    }

}

module.exports = TestPlan
