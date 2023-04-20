class Gabot{
    #property_id;
    #credentials_path;

    /**
     * Constructor
     * @param {string} property_id Google Analytics Property ID
     * @param {string} credentials_path Google Analytics Credentials Path
     */
    constructor(property_id, credentials_path){
        this.#property_id = property_id;
        this.#credentials_path = credentials_path;
    }
    
    /**
     * 
     * @param {start_date:string, end_date:string, activity_limit:string, action:string} data 
     * @returns {Promise<JSON>}
     */
    async runReport(data){
        const response = await fetch("./Connector.php", {
            method: "POST",
            body: JSON.stringify({
                property_id: this.#property_id,
                credentials_path: this.#credentials_path,
                data: data
            }),
            headers: {
                "Content-Type": "application/json"
            }
        });

        return response.json();
    }

    /**
     * Get active users by device
     * @param {string} start_date the start date of the report, default(28daysAgo)
     * @param {string} end_date the end date of the report, default(today)
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     * @returns {Promise}
     */
    async getActiveUsersByDevice(start_date = "28daysAgo", end_date = "today", activity_limit = "activeUsers"){
        return await this.runReport({"start_date":start_date, "end_date":end_date, "activity_limit":activity_limit, action:"getActiveUsersByDevice"});
    }

    /**
     * Get active users by os
     * @param {string} start_date the start date of the report, default(28daysAgo)
     * @param {string} end_date the end date of the report, default(today)
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     * @returns {Promise}
     */
    async getActiveUsersByOS(start_date = "28daysAgo", end_date = "today", activity_limit = "activeUsers"){
        return await this.runReport({"start_date":start_date, "end_date":end_date, "activity_limit":activity_limit, action:"getActiveUsersByOS"});
    }

    /**
     * Get active users by os
     * @param {string} start_date the start date of the report, default(28daysAgo)
     * @param {string} end_date the end date of the report, default(today)
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     * @returns {Promise}
     */
    async getActiveUsersByBrowser(start_date = "28daysAgo", end_date = "today", activity_limit = "activeUsers"){
        return await this.runReport({"start_date":start_date, "end_date":end_date, "activity_limit":activity_limit, action:"getActiveUsersByBrowser"});
    }

    /**
     * Get active users by os
     * @param {string} start_date the start date of the report, default(28daysAgo)
     * @param {string} end_date the end date of the report, default(today)
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     * @returns {Promise}
     */
    async getActiveUsersByCity(start_date = "28daysAgo", end_date = "today", activity_limit = "activeUsers"){
        return await this.runReport({"start_date":start_date, "end_date":end_date, "activity_limit":activity_limit, action:"getActiveUsersByCity"});
    }

    /**
     * Get active users by os
     * @param {string} start_date the start date of the report, default(28daysAgo)
     * @param {string} end_date the end date of the report, default(today)
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     * @returns {Promise}
     */
    async getActiveUsersByCountry(start_date = "28daysAgo", end_date = "today", activity_limit = "activeUsers"){
        return await this.runReport({"start_date":start_date, "end_date":end_date, "activity_limit":activity_limit, action:"getActiveUsersByCountry"});
    }

    /**
     * Get active users by os
     * @param {string} start_date the start date of the report, default(28daysAgo)
     * @param {string} end_date the end date of the report, default(today)
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     * @returns {Promise}
     */
    async asyncgetActiveUsersByCountryAndCity(start_date = "28daysAgo", end_date = "today", activity_limit = "activeUsers"){
        return await this.runReport({"start_date":start_date, "end_date":end_date, "activity_limit":activity_limit, action:"getActiveUsersByCountryAndCity"});
    }
}