import axios from "axios";
import { ref } from "vue";
// import { signInWithEmailAndPassword } from "@/services/firebaseConfig";
import { getAuth, signInWithEmailAndPassword as signInFirebase, } from "firebase/auth";
import { initializeApp } from "firebase/app";

const firebaseConfig = {
  apiKey: "AIzaSyAda3Bw4Dr0pXmDBNHwcR4EDWlByL81M4I",
  authDomain: "speedyrepair-6f70d.firebaseapp.com",
  databaseURL: "https://speedyrepair-6f70d-default-rtdb.firebaseio.com",
  projectId: "speedyrepair-6f70d",
  storageBucket: "speedyrepair-6f70d.appspot.com",
  messagingSenderId: "93950013309",
  appId: "1:93950013309:web:c70a629b99583ae9eea614",
  measurementId: "G-T3BC6KBKTK",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

const baseUrl = "https://speedyrepairanddelivery.com/api-delivery/";
// const baseUrl = 'http://localhost/codeigniter/';
let pwauth = localStorage.getItem("token");
const updateToken = () => {
  pwauth = localStorage.getItem("token");
};

const uidInfo = ref("");

export { uidInfo };

const requestConfig = {
  headers: {
    PWAUTH: pwauth,
  },
};

const decryptToken = (token) => {
  try {
    const bytes = crypto.AES.decrypt(token, "your-secret-key");
    const decryptedData = JSON.parse(bytes.toString(crypto.enc.Utf8));
    return decryptedData;
  } catch (error) {
    return null;
  }
};

//login
export async function loginAuth(userName, password) {
  try {
    const userCredential = await signInFirebase(auth, userName, password);
    const user = userCredential.user;
    uidInfo.value = user.uid;
    const credentials = btoa(userName + ":" + password);
    console.log("Credentials:", credentials);
    const res = await axios.get(baseUrl + "Users_v2", {
      headers: {
        LOGINAUTH: "Basic" + credentials,
      },
    });
    
    return res.data;
  } catch (error) {
    console.error("Error signing in or calling API:", error);
    throw error;
  }
}

//register
export async function register(email, password) {
  try {
    const res = await axios.post(baseUrl + "Users_v2", {
      email: email,
      password: password,
    });
    return res.data;
  } catch (error) {
    console.error("Error:", error);
    throw error;
  }
}

//register 2
// export async function register(data) {
//   try {
//     const res = await axios.post('/api/testRegisterVerified', data);
//     return res.data;
//   } catch (error) {
//     console.error("Error:", error);
//   }
// }

//update access
export async function updateAccess(accessID) {
  const requestData = {
    update_access: true,
    update_passwordAccMgt: false,
    forgot_password: false,
    accessID: accessID,
  };

  const res = await axios.put(baseUrl + "Users", requestData, requestConfig);
  return res.data;
}

//change password
export async function changePassword(
  oldPassword,
  newPassword,
  confPassword,
  otp
) {
  const requestData = {
    update_access: false,
    update_passwordAccMgt: true,
    forgot_password: false,
    oldPassword: oldPassword,
    newPassword: newPassword,
    confPassword: confPassword,
    otp: otp,
  };
  const res = await axios.put(baseUrl + "Users", requestData, requestConfig);
  return res.data;
}

//reset password
export async function resetPassword(email, newPassword, confPassword, token) {
  if (token == null) {
    token = "";
  }
  const requestData = {
    update_access: false,
    update_passwordAccMgt: false,
    forgot_password: true,
    email: email,
    forget_password_token: token,
    newPassword: newPassword,
    confPassword: confPassword,
  };
  const res = await axios.put(baseUrl + "Users", requestData);
  return res.data;
}

//Retrieves data from the Users endpoint using axios.
export async function retrieveData() {
  updateToken();
  console.log(pwauth);
  const res = await axios.get(baseUrl + 'Users_v2', {
    headers: {
      PWAUTH: pwauth,
    },
  })
  return res.data
}

//Retrieves data from the Users endpoint using axios.
// export async function retrieveData(token) {
//   updateToken();
//   const decrypted = decryptToken(token);
//   if (!decrypted) {
//     throw new Error('Invalid Token');
//   }

//   if (!decrypted.expires_at || Date.now() >= decrypted.expires_at) {
//     throw new Error('Token expired');
//   }

//   try {
//     const response = await axios.get('/customer', {
//       headers: { 'PWAUTH': token }
//     });
//     return response.data;
//   } catch (error) {
//     console.error("Error: " + error);
//   }
// };

// Asynchronous function for uploading files
export async function uploadData(fileInputs) {
  updateToken();
  const formData = new FormData();
  for (const fileInput of fileInputs) {
    formData.append("files[]", fileInput);
  }
  try {
    const res = await axios.post("Api/upload", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
    return res.data;
  } catch (error) {
    console.error("Error:", error);
    throw error;
  }
}

// Asynchronous function for viewing files
export async function viewFiles() {
  updateToken();
  try {
    const response = await axios.get("/api/upload");
    return response.data;
  } catch (error) {
    console.error("Error:", error);
    throw error;
  }
}

// Get all as Company User
export async function getCompDocs() {
  updateToken();
  await axios
    .get(baseUrl + "Company/docs", {
      header: {
        PWAUTH: pwauth,
      },
    })
    .then((res) => {
      return res;
    })
    .catch((error) => {
      console.error("Error:", error);
      throw error;
    });
}

// Get all as Driver User
export async function getDriverDocs() {
  updateToken();
  await axios
    .get(baseUrl + "Company/docs", {
      header: {
        PWAUTH: pwauth,
      },
    })
    .then((res) => {
      return res;
    })
    .catch((error) => {
      console.error("Error:", error);
      throw error;
    });
}

// export async function getDriverDocs(token) {
//   updateToken();
//   const decrypted = decryptToken(token);
//   if (!decrypted) {
//     throw new Error('Invalid Token');
//   }
//   try {
//     if (!decrypted.expires_at) {
//       const res = await filehelper.viewfiles(`Users/Company/${decrypted.UserID}`);
//       return res;
//     } else {
//       if (Date.now() >= decrypted.expires_at) {
//         throw new Error('Token is expired');
//       } else {
//         const jsonData = validateAndSanitizeUserInput(); // Implement this function
//         const res = await filehelper.viewfiles(`Users/Company/${jsonData.UserID}`);
//         if (!res) {
//           throw new Error('Internal Server Error :D');
//         }
//         return res;
//       }
//     }
//   } catch (error) {
//     console.error("Error: " + error);
//     throw new Error(error.message || 'Unauthorized access');
//   }
// };

//Get all data for Taxonomies Page
export async function getTaxonomies() {
  updateToken();
  const res = await axios
    .get(baseUrl + "TaxonomyHub", {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .catch((error) => {
      console.error("Error:", error);
      throw error;
    });
  return res;
}

//Adds a new size to the Taxonomies Page.
export async function addSize(categoryName, min, max, setFee) {
  updateToken();
  const setSize = {
    Category: categoryName,
    Min: min,
    Max: max,
    SetFee: setFee,
  };
  try {
    const res = await axios.post(
      baseUrl + "TaxonomyHub/size",
      setSize,
      requestConfig
    );
    return res;
  } catch (error) {
    console.error("Error:", error);
    throw error;
  }
}

//Adds a new weight to the Taxonomies Page.
export async function addWeight(categoryName, min, max, setFee) {
  updateToken();
  const setWeight = {
    Category: categoryName,
    Min: min,
    Max: max,
    SetFee: setFee,
  };
  try {
    const res = await axios.post(
      baseUrl + "TaxonomyHub/weight",
      setWeight,
      requestConfig
    );
    return res;
  } catch (error) {
    console.error("Error:", error);
    throw error;
  }
}

//Updates the data on the Taxonomies Page.
export async function updateData(data) {
  updateToken();
  await axios
    .put(baseUrl + "TaxonomyHub", data, {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .then((res) => {
      return res;
    })
    .catch((error) => {
      console.error("Error:", error);
      throw error;
    });
}

//Updates the size data on the Taxonomies Page.
export async function updateSize(id, categoryName, min, max, setFee) {
  updateToken();
  const updateSizes = {
    ID: id,
    Category: categoryName,
    Min: min,
    Max: max,
    SetFee: setFee,
  };
  try {
    const res = await axios.put(
      baseUrl + "TaxonomyHub/size",
      updateSizes,
      requestConfig
    );
    return res;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

//Deletes the size data on the Taxonomies Size Page.
export async function deleteSize(id) {
  updateToken();
  await axios
    .delete(baseUrl + "TaxonomyHub/size/", {
      headers: {
        PWAUTH: pwauth,
        DELID: id,
      },
    })
    .then((res) => {
      return res;
    })
    .catch((err) => {
      console.error("Error: " + err);
    });
}

//Updates the weight data on the Taxonomies Page.
export async function updateWeight(id, categoryName, min, max, setFee) {
  updateToken();
  const updateWeights = {
    ID: id,
    Category: categoryName,
    Min: min,
    Max: max,
    SetFee: setFee,
  };
  try {
    const res = await axios.put(
      baseUrl + "TaxonomyHub/weight",
      updateWeights,
      requestConfig
    );
    return res;
  } catch (err) {
    console.error("Weight was not updated");
  }
}

//Deletes the weight data on the Taxonomies Weight Page.
export async function deleteWeight(id) {
  updateToken();
  await axios
    .delete(baseUrl + "TaxonomyHub/weight/", {
      headers: {
        PWAUTH: pwauth,
        DELID: id,
      },
    })
    .then((res) => {
      return res;
    })
    .catch((err) => {
      console.error("Error: " + err);
    });
}

//Get all data for Fees Page
export async function getFees() {
  updateToken();
  const res = await axios
    .get(baseUrl + "App", {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .catch((err) => {
      console.error("Error: " + err);
    });
  return res;
}

//Updates the data on the Fees Page.
export async function updateFees(data) {
  updateToken();
  const res = await axios
    .put(baseUrl + "App", data, {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .catch((err) => {
      console.error("Error: " + err);
    });
  return res;
}

//Get all data for Vehicles Page
export async function getVehicles() {
  updateToken();
  const res = await axios
    .get(baseUrl + "Vehicles", {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .catch((err) => {
      console.error("Error: " + err);
    });
  return res;
}

//Get all data for Fees Page
// export async function getDriverDocs() {
//   updateToken();
//   await axios
//     .get(baseUrl + "Company/docs", {})
//     .then((res) => {
//       return res;
//     })
//     .catch((err) => {
//       console.error("Error: " + err);
//     });
// }

//Get all data for Request Priority Page
// export async function getDriverDocs() {
//   updateToken();
//   await axios
//     .get(baseUrl + "Company/docs", {})
//     .then((res) => {
//       return res;
//     })
//     .catch((err) => {
//       console.error("Error: " + err);
//     });
// }

// ========COMPANY MANAGEMENT FUNCTIONS========

//Creates a company with the given information and documents.
export async function createCompany(
  compName,
  compAddr,
  townCity,
  compState,
  compZip,
  docs
) {
  updateToken();
  const formData = new FormData();

  // Append company data
  formData.append("CompName", compName);
  formData.append("CompAddr", compAddr);
  formData.append("TownCity", townCity);
  formData.append("CompState", compState);
  formData.append("CompZip", compZip);

  // Append files
  for (const doc of docs) {
    formData.append("files[]", doc);
  }

  try {
    const response = await axios.post(baseUrl + "Company", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        PWAUTH: pwauth,
      },
    });
    return response.data;
  } catch (error) {
    console.error("Error creating company:", error);
    throw error;
  }
}

//Retrieves data from the Company API endpoint.
export async function compRetrieveData() {
  updateToken();
  console.log();
  const res = await axios
    .get(baseUrl + "Company", {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .catch((err) => {
      console.error("Error: " + err);
    });
  return res.data;
}

//Sets the status of a company.
export async function setCompStatus(companyID, status) {
  updateToken();
  const requestData = {
    CompanyID: companyID,
    Status: status,
  };
  await axios
    .put(baseUrl + "Company", requestData, {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .then((res) => {
      return res.data;
    })
    .catch((err) => {
      console.error(err);
      return err.data;
    });
}

//Asynchronous function for setting a company profile picture.
export async function uploadProfilePicture(fileInputs) {
  updateToken();
  const formData = new FormData();
  formData.append("file", fileInputs);
  try {
    const res = await axios.post(baseUrl + "Customer/upload", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        PWAUTH: pwauth,
      },
    });
    console.log("Upload successful:", res.data);
  } catch (error) {
    console.error("Error uploading files:", error);
  }
}

// ========DRIVER MANAGEMENT FUNCTIONS========

//Creates a driver with the given information and documents.
export async function createDriver(
  firstName,
  lastName,
  addr,
  townCity,
  state,
  zip,
  licenseNumber,
  vehicleType,
  docs
) {
  updateToken();
  const formData = new FormData();

  // Append company data
  formData.append("FirstName", firstName);
  formData.append("LastName", lastName);
  formData.append("Address", addr);
  formData.append("TownCity", townCity);
  formData.append("State", state);
  formData.append("Zip", zip);
  formData.append("LicenseNumber", licenseNumber);
  // formData.append("VehicleType", vehicleType);

  // Append files
  for (const doc of docs) {
    formData.append("files[]", doc);
  }

  try {
    const response = await axios.post(baseUrl + "DeliveryDrivers", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        PWAUTH: pwauth,
      },
    });
    //console.log('Upload successful:', response.data);
    return response.data;
  } catch (error) {
    console.error("Error creating company:", error);
    throw error;
  }
}

//Retrieves data from the DeliveryDrivers API endpoint.
export async function driverRetrieveData() {
  updateToken();
  const res = await axios
    .get(baseUrl + "DeliveryDrivers", {
      headers: {
        PWAUTH: pwauth,
      },
    })
    .catch((err) => {
      console.error("Error: " + err);
    });
  return res.data;
}

// export async function driverRetrieveData(token) {
//   updateToken();
//   const decrypted = decryptToken(token);
//   if (!decrypted) {
//     throw new Error('Unauthorized access');
//   }
//   try {
//     const url = !decrypted.expires_at ? `${baseUrl}/deliverydrivers/${decrypted.UserID}` : `${baseUrl}/deliverydrivers`;
//     const res = await axios.get(url, {
//       headers: {
//         PWAUTH: token
//       }
//     });
//     return res.data;
//   } catch (error) {
//     console.error("Error: " + error);
//     throw new Error(error.response ? error.response.data.message : 'Unauthorized access');
//   }
// };

//Updates the driver status in the system.
export async function setDriverStatus(driverID, status) {
  updateToken();
  const requestData = {
    DriverID: driverID,
    Status: status,
  };
  await axios
    .put(baseUrl + "DeliveryDrivers", requestData, requestConfig)
    .then((res) => {
      return res.data;
    })
    .catch((err) => {
      console.error(err);
      return err.data;
    });
}

// export async function setDriverStatus(token, data) {
//   updateToken();
//   const decrypted = decryptToken(token);
//   if (!decrypted) {
//     throw new Error('Unauthorized access');
//   }
//   if (!decrypted.expires_at) {
//     throw new Error('Unauthorized access');
//   } else {
//     if (Date.now() >= decrypted.expires_at) {
//       throw new Error('Token is expired');
//     } else {
//       try {
//         const res = await axios.put(`${baseUrl}/deliverydrivers/status`, data, {
//           headers: {
//             PWAUTH: token
//           }
//         });
//         if (res.data) {
//           return res.data;
//         } else {
//           throw new Error('Status has failed to update');
//         }
//       } catch (error) {
//         console.error("Error: " + error);
//         throw new Error(error.response ? error.response.data.message : 'Status has failed to update');
//       }
//     }
//   }
// }

//Creates a driver with the given information and documents.
export async function postDeliveryDriver(token, postData) {
  updateToken();
  const validated = decryptToken(token);
  const pattern = /^[a-zA-Z0-9_]+$/;
  if (!validated) throw new Error("Token is Invalid");
  if (!validated.UserID) throw new Error("Token is empty");

  if (validatePostData(postData, pattern)) {
    try {
      const res = await axios.post(`${baseUrl}/deliverydrivers`, postData, {
        headers: {
          PWAUTH: token,
        },
      });

      const upld = await filehelper.uploadMultiple(
        `Users/Driver/${validated.UserID}/docs`,
        "files",
        true
      );
      if (res.data && upld) {
        console.log("Driver created successfully:", res.data);
      } else {
        throw new Error("Internal Server Error");
      }
    } catch (error) {
      console.error("Error: " + error);
      throw new Error(
        error.response ? error.response.data.message : "Internal Server Error"
      );
    }
  } else {
    throw new Error("Input fields must not include white spaces");
  }
}

//Asynchronous function for setting a driver profile picture.
export async function driverSetPfp(fileInputs) {
  updateToken();
  const formData = new FormData();
  formData.append("file", fileInputs);
  try {
    const res = await axios.post(baseUrl + "DeliveryDrivers/upload", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        PWAUTH: pwauth,
      },
    });
    console.log("Upload successful:", res.data);
  } catch (error) {
    console.error("Error uploading files:", error);
  }
}

// export async function driverSetPfp(token, file) {
//   updateToken();
//   const decrypted = decryptToken(token);
//   if (!decrypted) {
//     throw new Error('Invalid Token');
//   }

//   const currentTimestamp = Date.now();
//   try {
//     const upload = await filehelper.upload(`Users/Company/${decrypted.UserID}/pfp/${currentTimestamp}`, file, true);
//     if (upload) {
//       const path = upload.result.path;
//       const res = await axios.post(`${baseUrl}/profile-picture`, { path }, {
//         headers: {
//           PWAUTH: token
//         }
//       });

//       if (res.data) {
//         return res.data;
//       } else {
//         throw new Error('Internal Server Error');
//       }
//     } else {
//       throw new Error('Internal Server Error');
//     }
//   } catch (error) {
//     console.error("Error: " + error);
//     throw new Error(error.response ? error.response.data.message : 'Unauthorized access');
//   }
// }

//Updates the driver in the system.
export async function updateDriver(
  firstName,
  lastName,
  addr,
  townCity,
  state,
  zip,
  licenseNumber,
  vehicleType
) {
  updateToken();
  const updatedDriverData = {
    FirstName: firstName,
    LastName: lastName,
    Address: addr,
    TownCity: townCity,
    State: state,
    Zip: zip,
    LicenseNumber: licenseNumber,
    VehicleType: vehicleType,
  };
  try {
    const response = await axios.put(
      baseUrl + "DeliveryDrivers",
      updatedDriverData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
          PWAUTH: pwauth,
        },
      }
    );
    return response.data;
  } catch (error) {
    console.error("Error creating company:", error);
    throw error;
  }
}

const validatePostData = (postData, pattern) => {
  const { FirstName, LastName, Address, TownCity, State, Zip, LicenseNumber } =
    postData;
  return (
    FirstName &&
    LastName &&
    Address &&
    TownCity &&
    State &&
    Zip &&
    LicenseNumber &&
    [FirstName, LastName, Address, TownCity, State, Zip, LicenseNumber].every(
      (field) => pattern.test(field)
    )
  );
};

// ========VEHICLE MANAGEMENT FUNCTIONS========

//Add a new vehicle with the given parameters to the database.
export async function vehicleAdd(id, type, baseDistance, baseFee, distanceFee) {
  updateToken();
  const createVehicle = {
    VehicleType: type,
    BaseDistance: baseDistance,
    BaseFee: baseFee,
    DistanceFee: distanceFee,
  };
  try {
    const res = await axios.post(
      baseUrl + "Vehicles",
      createVehicle,
      requestConfig
    );
    console.log("Successfully updated the size");
    return res;
  } catch (err) {
    console.error("Weight was not updated");
  }
}

//Retrieves data from the Vehicles API endpoint.
export async function vehicleRetrieve() {
  return await axios
    .get(baseUrl + "Vehicles", requestConfig)
    .then((res) => {
      return res.data;
    })
    .catch((err) => {
      console.error(err);
      return {};
    });
}

//Updates the vehicle in the system.
export async function vehicleUpdate(
  id,
  type,
  baseDistance,
  baseFee,
  distanceFee
) {
  updateToken();
  const updateVehicle = {
    TypeID: id,
    VehicleType: type,
    BaseDistance: baseDistance,
    BaseFee: baseFee,
    DistanceFee: distanceFee,
  };
  try {
    const res = await axios.put(
      baseUrl + "Vehicles",
      updateVehicle,
      requestConfig
    );
    return res;
  } catch (err) {
    console.error("Weight was not updated");
    console.error("", err);
  }
}

// ========EMPLOYEE MANAGEMENT FUNCTIONS========
// Create sub-users for company accounts
export async function employeeRegistration(
  email,
  password,
  firstName,
  lastName
) {
  updateToken();
  await axios
    .post(
      baseUrl + "Company/employee",
      {
        email: email,
        password: password,
        FirstName: firstName,
        LastName: lastName,
      },
      {
        headers: {
          PWAUTH: pwauth,
        },
      }
    )
    .then((res) => {
      return res.data;
    })
    .catch((err) => {
      console.error(err);
    });
}

// Get all Company User
export async function retrieveEmployee() {
  updateToken();
  try {
    const res = await axios.get(baseUrl + "Company/employee", {
      headers: {
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (error) {
    console.error("Error: " + error);
  }
}

// Set employee status
export async function setEmployeeStatus(sub_userID, status) {
  updateToken();
  const requestData = {
    CompanyID: sub_userID,
    Status: status,
  };
  await axios
    .put(baseUrl + "Company/employee", requestData, requestConfig)
    .then((res) => {
      return res.data;
    })
    .catch((err) => {
      console.error(err);
      return err.data;
    });
}

//Asynchronous function for setting a employee profile picture.
export async function setEmployeePfp(fileInputs) {
  updateToken();
  const formData = new FormData();
  formData.append("file", fileInputs);
  try {
    const res = await axios.post(baseUrl + "Company/employee/pfp", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (error) {
    console.error("Error uploading files:", error);
  }
}

// ========CUSTOMER MANAGEMENT FUNCTIONS========

//Create a new customer with the given parameters to the database.
export async function createCustomer(postData) {
  updateToken();
  try {
    const response = await axios.post(baseUrl + "Customer", postData, {
      headers: {
        PWAUTH: pwauth,
      },
    });
    return response.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

//Retrieves data from the Customer API endpoint.
export async function userRetrieveData() {
  updateToken();
  try {
    const res = await axios.get(baseUrl + "Customer/user", {
      //Initial value is "Customer/user" | "/customer/${decrypted.UserID}"
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
  updateToken();
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

// export async function customerRetrieveData(token) {
//   updateToken();
//   const decrypted = decryptToken(token);
//   if (!decrypted) {
//     console.error('Invalid Token');
//   }

//   try {
//     const response = await axios.get(`/customer/${decrypted.UserID}`, {
//       headers: { 'PWAUTH': token }
//     });
//     return response.data;
//   } catch (error) {
//     console.error("Error: " + error);
//   }
// };

//Updates the customer in the system.
export async function updateCustomer(data) {
  updateToken();
  try {
    const res = await axios.put(baseUrl + "Customer", data, {
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

// export async function updateCustomer(token, data) {
//   updateToken();
//   const decrypted = decryptToken(token);
//   if (!decrypted || !decrypted.UserID) {
//     console.error('Token is Invalid or empty');
//   }

//   try {
//     const response = await axios.put(`/customer/${decrypted.UserID}`, data, {
//       headers: { 'PWAUTH': token }
//     });
//     return response.data;
//   } catch (error) {
//     console.error("Error: " + error);
//   }
// };

//Updates the customer status in the system.
export async function updateStatus(pwauth, data) {
  try {
    const res = await axios.put(baseUrl + "Company", data, {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

export async function getPfp(pwauth) {
  try {
    const res = await axios.get(baseUrl + "Company/pfp", {
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

export async function createEmployee(pwauth, postData) {
  try {
    const res = await axios.post(baseUrl + "Company/employee", postData, {
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

export async function uploadPfp(pwauth, file) {
  try {
    const formData = new FormData();
    formData.append("file", file);

    const res = await axios.post(baseUrl + "Company/pfp", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

export async function updateCompanyDetails(pwauth, data) {
  try {
    const res = await axios.put(baseUrl + "Company/update", data, {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

export async function updateEmployee(pwauth, data) {
  try {
    const res = await axios.put(baseUrl + "Company/employee", data, {
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

// ========BOOKING HISTORY========
export async function getBookingHistory() {
  try {
    const res = await axios.get(baseUrl + `BookService`, {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    console.info(pwauth);
    console.log("baseUrl", baseUrl);
    console.log("getBookingHistory: ", res.data);
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

// ========BOOKING FUNCTIONS========
export async function createBooking(data) {
  try {
    const res = await axios.post(baseUrl, "/BookService", data, {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

// ========BOOKING FUNCTIONS========
export async function updateBooking(data) {
  try {
    const res = await axios.put(baseUrl, "BookService", data, {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

export async function addReview(driverID, customerId, comment, rating) {
  const data = {
    driver_id: driverID,
    customerID: customerId,
    comment: comment,
    rating: rating,
  };

  console.log(data);
  updateToken();
  try {
    const res = await axios.post(baseUrl + "Reviews", data, {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

export async function getReviews() {
  updateToken();
  try {
    const res = await axios.get(baseUrl + "Reviews", {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}

export async function addReport(report) {
  updateToken();
  try {
    console.log(baseUrl + "Reports");
    const res = await axios.post(baseUrl + "Report", report, {
      headers: {
        "Content-Type": "application/json",
        PWAUTH: pwauth,
      },
    });
    return res.data;
  } catch (err) {
    console.error("Error: " + err);
    throw err;
  }
}
