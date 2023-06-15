import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
import { setLoading } from "@/utils/loading";

const module = "get-data-selects";
const apiPath = import.meta.env.VITE_API_PATH;

export default function selectServices() {
  const data = reactive({
    all: [],
    one: {},
  });

  const neighborhoods = reactive({
    all: [],
    one: {},
  });

  const placeTypes = reactive({
    all: [],
    one: {},
  });

  const nacs = reactive({
    all: [],
    one: {},
  });

  const groupBeneficiaries = reactive({
    all: [],
    one: {},
  });

  const beneficiariesTable = reactive({
    all: [],
    one: {},
  });

  const status = reactive({
    all: [],
    one: {},
  })

  const errors = ref("");

  const getSelectAll = async () => {
    try {
      let response = await axiosInstance
        .get(`/${apiPath}/${module}`);
      if (response.status === 200) {
        data.all = response.data;
        // print(JSON.stringify(`${response.data}`))
      }
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };
  const getStatus = async() => {
    try {
        let response = axiosInstance
          .get(`/${apiPath}/get_status`)
          .then(function (response) {
            if (response.status === 200) {
              status.all = response.data;
            }
          });
        return response;
    } catch (error) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  }
  const getPlaceTypes = async() => {
    try {
        let response = axiosInstance
          .get(`/${apiPath}/get_place_types`)
          .then(function (response) {
            if (response.status === 200) {
              placeTypes.all = response.data;
            }
          });
        return response;
    } catch (error) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  }
  const getNeighborhood = async(nac_id) => {
    try {
      if (nac_id == null){
        nac_id = 0;
      }
        let response = axiosInstance
          .get(`/${apiPath}/get_neighborhoods/${nac_id}`)
          .then(function (response) {
            if (response.status === 200) {
              neighborhoods.all = response.data;
            }
          });
        return response;
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  }
  const getNacs = async() => {
    try {
      let response = axiosInstance
        .get(`/${apiPath}/get_nacs`)
        .then(function (response) {
          if (response.status === 200) {
            nacs.all = response.data;
          }
        });
      return response;
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  }
  const getGroupBeneficiaries = async() => {
    try {
      let response = axiosInstance
        .get(`/${apiPath}/get_group_beneficiaries`)
        .then(function (response) {
          if (response.status === 200) {
            groupBeneficiaries.all = response.data;
          }
        });
      return response;
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  }
  const getBeneficiariesTable = async() => {
    try {
        let response = axiosInstance
          .get(`/${apiPath}/get_beneficiaries_table`)
          .then(function (response) {
            if (response.status === 200) {
              beneficiariesTable.all = response.data;
            }
          });
        return response;
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  }
const getSeedBeds=async()=>{
  try {
      setLoading(true);
      let response = axiosInstance.get(`/${apiPath}/culturalSeedbeds`).finally(()=>setLoading(false));
      if(response.status==200){
      //  console.log("Success get ");
        data.all = response.data;
      }else{
       // console.log("Exception on get data culturalSeedBeds");
       // console.log(`status code : ${ response.status}`);
      }
       return response;
  } catch (error) {
    alerts.custom_validation(e.response.data.error ?? e.response.data.message);
  }
}

const getMethodologicalSheetsOne=async()=>{
  try {
      setLoading(true);
      let response = axiosInstance.get(`/${apiPath}/methodologicalsheetsone`).finally(()=>setLoading(false));
      if(response.status==200){
     //   console.log("Success get getMethodologicalSheetsOne");
        data.all = response.data;
      }else{
        console.log("Exception on get data methodologicalsheetsonemethodologicalsheetsone");
        console.log(`status code : ${ response.status}`);
      }
       return response;
  } catch (error) {
    alerts.custom_validation(e.response.data.error ?? e.response.data.message);
  }
}
  return {
    data,
    errors,
    neighborhoods,
    placeTypes,
    nacs,
    groupBeneficiaries,
    beneficiariesTable,
    status,
    getStatus,
    getGroupBeneficiaries,
    getBeneficiariesTable,
    getNacs,
    getPlaceTypes,
    getSelectAll,
    getSeedBeds,
    getMethodologicalSheetsOne,
    getNeighborhood
  };
}
// export default{
//   getSelectAll() {
//     return axiosInstance.get(`/${apiPath}/${module}`).then(function (response) {
//     }).catch(function (error) {

//     });
//   }

// }
