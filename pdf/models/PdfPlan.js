const { jsPDF } = require("jspdf")
const fs = require("fs")
require('../fonts/Inter-normal');
require('../fonts/Inter-bold');

class PdfPlan
{
    constructor()
    {
        this._doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4',
            putOnlyUsedFonts: true,
        });

        this._data = [];
        this._contentGroups = [];

        this._settings = {
            margin: { left: 20, top: 45, right: 20, bottom: 24 },
            blockSpace: 2,
        }
    }

    withData(data) {
        this._data = data;
        return this;
    }

    render()
    {
        // retrieve content blocks from callback and add them
        this.contentBlocks(this._data).forEach(block => this._addContentBlock(block));
        // add required empty pages
        this._createRequiredEmptyPages(this._contentGroups.length);

        // loop through pages and add content / layout
        this._loopPages(pageInfo => {
            this.pageLayout(
                this._doc,
                this._settings,
                pageInfo.pageNumber,
                this._doc.getNumberOfPages(),
            );

            if (this._contentGroups.length <= 0) {
                return false;
            }

            const pageBlocks = this._contentGroups[pageInfo.pageNumber - 1];

            const xPosition = this._settings.margin.left;
            let yPosition = this._settings.margin.top;

            pageBlocks.forEach(block => {
                block.addTo(this._doc).at(xPosition, yPosition).render();
                yPosition += block.height + this._settings.blockSpace
            });
        });

        return this;
    }

    save(path)
    {
        return this._doc.save(path);
    }

    /**
     * ------------------------
     * CALLBACKS
     * ------------------------
     * Overwrite in child-class to add content
     *
     */

    pageLayout(page, settings, pageNumber, numberOfPages) {}
    contentBlocks(data) { return []; }

    /**
     * ------------------------
     * CONTENT BLOCKS HANDLING
     * ------------------------
     */

    _addContentBlock(block)
    {
        // check if the content groups array is initiated
        if (this._contentGroups.length <= 0) this._contentGroups.push([]);

        // check if there is enough space to add a block into the last group
        const group = this._contentGroups[this._contentGroups.length-1];

        let groupHeight = 0;
        group.forEach(block => groupHeight += block.height + this._settings.blockSpace)

        if (this._availableContentHeightOnPage() <= (groupHeight + block.height)) {
            this._contentGroups.push([]);
        }
        // add the block to the last group
        this._contentGroups[this._contentGroups.length-1].push(block);
    }

    /**
     * ---------------
     * HELPERS
     * ---------------
     */
    _loopPages(callback)
    {
        // render headers
        for (let i = 1; i <= this._doc.getNumberOfPages(); i++) {
            this._doc.setPage(i);
            callback(this._doc.getCurrentPageInfo());
        }
    }

    _createRequiredEmptyPages(totalPages)
    {
        while (this._doc.getNumberOfPages() < totalPages) {
            this._doc.addPage();
        }

        // prepare default starting point
        this._doc.setPage(1);
    }

    _availableContentHeightOnPage()
    {
        return this._doc.getPageHeight() - this._settings.margin.top - this._settings.margin.bottom;
    }
}

module.exports = PdfPlan;
