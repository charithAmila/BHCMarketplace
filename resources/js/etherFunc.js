import { ethers } from 'ethers';
const provider = new ethers.providers.Web3Provider(window.ethereum);
const signer = provider.getSigner();

function checkConnection() {

}

async function signMessage(message) {
    return await signer.signMessage(message);
}
function toAddress(addressString) {
    return ethers.utils.isAddress(addressString) ? addressString :
        ethers.utils.getAddress('0x0000000000000000000000000000000000000000');
}


export { signMessage, toAddress }