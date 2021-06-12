
const AxiosLibrary = require('axios').default
require('dotenv').config()

// const MeetingsPlan = require("./models/MeetingsPlan")
// const DataSourceReader = require("./models/DataSourceReader")
// const ServiceMeetingsPlan = require("./models/ServiceMeetingsPlan");
// const AwaySpeakerPlan = require("./models/AwaySpeakerPlan");

const AwaySpeakerPlan = require("./documents/AwaySpeakerPlan");
// const AwaySpeakerPlan = require("./documents/TestPlan");

const axios = AxiosLibrary.create({
    baseURL: new URL( '/api/pdf/data', process.env.APP_URL ).href,
    timeout: 1000,
    headers: {"Authorization" : `Bearer ${process.env.PDF_GENERATION_TOKEN}`}
});

axios.get('/away-speaker').then(response => {
    const pdf = new AwaySpeakerPlan(response.data.data);
    pdf.render().save('pdf/docs/eigene-redner-alle.pdf');

    require("child_process").exec('open ./pdf/docs/eigene-redner-alle.pdf');
}).catch();

// const publicMeetingsPlan = new MeetingsPlan();
// publicMeetingsPlan.withData(DataSourceReader.read('weekend-meetings.json'))
//     .render()
//     .save(`pdf/docs/vortragsplanung.pdf`);
// require("child_process").exec('open ./pdf/docs/vortragsplanung.pdf');

// const serviceMeetingsPlan = new ServiceMeetingsPlan();
// serviceMeetingsPlan.withData(DataSourceReader.read('service-meetings.json'))
//     .render()
//     .save('pdf/docs/treffpunkte.pdf');
// require("child_process").exec('open ./pdf/docs/treffpunkte.pdf');
