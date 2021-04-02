<script>
import React from "react";
import { render } from "react-dom";
import bsc from "@binance-chain/bsc-use-wallet";
import { UseWalletProvider } from "use-wallet";


    const wallet = useWallet();
    const blockNumber = wallet.getBlockNumber();
    export default {
        methods:{
render(){
    return (
       <h1></h1>
    );
}

    }
    }



</script>
