import { ethers } from "ethers";
import { toAddress, checkConnection } from "./etherFunc.js";
const provider = new ethers.providers.Web3Provider(window.ethereum);
const signer = provider.getSigner();

var address = toAddress(checkConnection());

window.connected_account = address.toLowerCase();

async function checkliked(contract, id) {
    var res = await axios.get("/like");
    var data = res.data;
    var valObj = data.likes.filter(function(elem) {
        if (
            elem.token_id == id &&
            elem.contract == contract &&
            elem.address == connected_account
        )
            return elem.token_id;
    });
    if (valObj.length > 0) {
        return true;
    }

    return false;
}

async function LikeController(contract, id) {
    if (!address) {
        alert("Please connect your wallet first");
    } else {
        let data = {};
        var signature;
        var signature_onida;
        var return_array = await checkliked(contract, id);
        if (return_array) {
            signature_onida = false;
        } else {
            signature_onida = true;
        }

        if (signature_onida) {
            signature = await signer.signMessage(
                "I would like to like token : " + contract + id
            );
        } else {
            signature = "not required at the moment!";
        }
        data.sign = signature;
        data.contract = contract;
        data.token_id = id;
        data.address = address.toLowerCase();

        var output;
        await axios
            .post("/like", data, {})
            .then(function(response) {
                output = response.data;
            })
            .catch(function(error) {});
        return output;
    }
}

export { LikeController };