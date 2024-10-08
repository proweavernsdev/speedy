import axios from "axios";

const baseUrl = "https://speedyrepairanddelivery.com/api-delivery/";

let pwauth = JSON.parse(localStorage.getItem("token"));
const requestConfig = {
    headers: {
        PWAUTH: pwauth,
    },
};

let updateToken = () => {
    pwauth = JSON.parse(localStorage.getItem("token"));
};

//Create a new customer with the given parameters to the database.
export async function createCustomer(pwauth, postData) {
    try {
        const res = await axios.post(baseUrl + "Customer", postData, {
            headers: {
                PWAUTH: pwauth,
            },
        });
        return res.data;
    } catch (err) {
        console.error("Error: " + err);
        throw err;
    }
}

//Retrieves data from the Customer API endpoint.
export async function userRetrieveData(pwauth) {
    try {
        const res = await axios.get(baseUrl + "Customer/user", {
            headers: {
                PWAUTH: pwauth,
            },
        });
        return res.data;
    } catch (err) {
        console.error("Error: " + err);
        throw err;
    }
}

//Retrieves data from the Customer API endpoint.
export async function customerRetrieveData() {
    updateToken()
    try {
      const res = await axios.get(baseUrl + 'Customer/user', {
        headers: {
          PWAUTH: pwauth,
        },
      })
      return res.data
    } catch (err) {
      console.error('Error: ' + err)
      throw err
    }
  }
  
//Updates the customer in the system.
export async function updateCustomer(data) {
updateToken()
try {
    const res = await axios.put(baseUrl + 'Customer', data, {
    headers: {
        PWAUTH: pwauth,
    },
    })
    return res.data
} catch (err) {
    console.error('Error: ' + err)
    throw err
}
}

//Updates the customer status in the system.
export async function updateStatus(pwauth, data) {
try {
    const res = await axios.put(baseUrl + 'Company', data, {
    headers: {
        'Content-Type': 'application/json',
        PWAUTH: pwauth,
    },
    })
    return res.data
} catch (err) {
    console.error('Error: ' + err)
    throw err
}
}

//Asynchronous function for getting a customer profile picture.
export async function getPfp(pwauth) {
try {
    const res = await axios.get(baseUrl + 'Company/pfp', {
    headers: {
        PWAUTH: pwauth,
    },
    })
    return res.data
} catch (err) {
    console.error('Error: ' + err)
    throw err
}
}

//Asynchronous function for creating an employee.
export async function createEmployee(pwauth, postData) {
try {
    const res = await axios.post(baseUrl + 'Company/employee', postData, {
    headers: {
        PWAUTH: pwauth,
    },
    })
    return res.data
} catch (err) {
    console.error('Error: ' + err)
    throw err
}
}

//Asynchronous function for setting an employee profile picture.
export async function uploadPfp(pwauth, file) {
try {
    const formData = new FormData()
    formData.append('file', file)

    const res = await axios.post(baseUrl + 'Company/pfp', formData, {
    headers: {
        'Content-Type': 'multipart/form-data',
        PWAUTH: pwauth,
    },
    })
    return res.data
} catch (err) {
    console.error('Error: ' + err)
    throw err
}
}

//Asynchronous function for updating the company details.
export async function updateCompanyDetails(pwauth, data) {
try {
    const res = await axios.put(baseUrl + 'Company/update', data, {
    headers: {
        'Content-Type': 'application/json',
        PWAUTH: pwauth,
    },
    })
    return res.data
} catch (err) {
    console.error('Error: ' + err)
    throw err
}
}

//Asynchronous function for updating the employee details.
export async function updateEmployee(pwauth, data) {
try {
    const res = await axios.put(baseUrl + 'Company/employee', data, {
    headers: {
        PWAUTH: pwauth,
    },
    })
    return res.data
} catch (err) {
    console.error('Error: ' + err)
    throw err
}
}

