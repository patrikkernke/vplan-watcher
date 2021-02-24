const fs = require("fs")

class DataSourceReader {

    static read(filename) {
        let data = [];

        try {
            data = JSON.parse(
                fs.readFileSync(`./pdf/sources/${filename}`, 'utf8')
            );
        } catch (err) {
            console.log(`Error reading data source file from disk: ${err}`);
        }

        return data;
    }

}

module.exports = DataSourceReader;
