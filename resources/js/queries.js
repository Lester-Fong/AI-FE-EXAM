import axios from "axios";
import CryptoJS from "crypto-js";

let user_queries = {
    action_user: `mutation user($user: user_input) {
        user(user: $user) {
            error,
            message,
            access_token,
            refresh_token,
            token_expiration,
            email,
        }
    }`,

    front_article: `query article($link: String, $action_type: String) {
        article(link: $link, action_type: $action_type) {
            article_id,
            original_id,
            title,
            content,
            image,
            link,
            status,
            writer {
                user_id,
                firstname,
                lastname,
            },
            editor {
                user_id,
                firstname,
                lastname,
            },
            date,
            company {
                company_id,
                original_id,
                name,
            }
        }
    }`,

    user: `query ($action_type: String) {
        user(action_type: $action_type) {
            user_id
            firstname,
            lastname,
            email,
            user_type,
            user_status,
        },
    }`,
    save_user: `mutation user($user: user_input) {
        user(user: $user) {
            error,
            message,
            user {
                user_id
                firstname,
                lastname,
                email,
                user_type,
            }
        }
    }`,

    article: `query article($article_id: String, $action_type: String) {
        article(article_id: $article_id, action_type: $action_type) {
            article_id,
            original_id,
            title,
            content,
            image,
            link,
            status,
            writer {
                user_id,
                firstname,
                lastname,
            },
            editor {
                user_id,
                firstname,
                lastname,
            },
            date,
            company {
                company_id,
                original_id,
                name,
            }
        }
    }`,

    save_article: `mutation article($article: article_input, $file: Upload) {
        article(article: $article, file: $file) {
            error,
            message,
        }
      }`,

    company: `query company($company_id: String, $action_type: String) {
        company(company_id: $company_id, action_type: $action_type) {
            company_id,
            original_id,
            logo,
            name,
            status,
        }
    }`,

    save_company: `mutation company($company: company_input, $file: Upload) {
        company(company: $company, file: $file) {
            error,
            message,
        }
    }`,
};

const userQueries = ["user", "save_user", "article", "save_company", "save_article", "company"];
const uploadQueries = ["save_article", "save_company"];

const getApiUrl = (queryName) => {
    let segment = "";

    if (userQueries.some((q) => q === queryName)) {
        segment = "/user";
    }

    return `/graphql${segment}`;
};

const user_query = (queryName, queryVariables) => {
    var token = "";
    var secret_passphrase = process.env.MIX_SECRET_PASSPHRASE;

    if (userQueries.some((q) => q === queryName)) {
        const encryptedToken = sessionStorage.getItem("user_api_token");
        token = CryptoJS.AES.decrypt(encryptedToken, secret_passphrase).toString(CryptoJS.enc.Utf8);
    }

    // FOR UPLOADING FILES
    if (uploadQueries.some((q) => q === queryName)) {
        let bodyFormData = new FormData();

        bodyFormData.set("operations", JSON.stringify({ query: user_queries[queryName], variables: queryVariables }));

        bodyFormData.set("operationName", null);

        bodyFormData.set(
            "map",
            JSON.stringify({
                file: ["variables.file"],
                file1: ["variables.file1"],
                file2: ["variables.file2"],
                file3: ["variables.file3"],
                file4: ["variables.file4"],
                file5: ["variables.file5"],
            })
        );
        bodyFormData.append("file", queryVariables.file);
        bodyFormData.append("file1", queryVariables.file1);
        bodyFormData.append("file2", queryVariables.file2);
        bodyFormData.append("file3", queryVariables.file3);
        bodyFormData.append("file4", queryVariables.file4);
        bodyFormData.append("file5", queryVariables.file5);

        var upload_url = getApiUrl(queryName);

        return axios.post(upload_url, bodyFormData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${token}`,
            },
        });
    }

    let options = {
        url: getApiUrl(queryName),
        method: "POST",
        data: {
            query: user_queries[queryName],
        },
    };

    if (queryVariables) {
        options.data.variables = queryVariables;
    }

    if (token) {
        options.headers = {
            Authorization: `Bearer ${token}`,
            "Cache-Control": "no-cache, max-age=3600",
        };
    }

    return axios(options);
};

export default user_query;
