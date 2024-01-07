import Onboard from '@web3-onboard/core';
import injectedModule from '@web3-onboard/injected-wallets';
import ledgerModule from '@web3-onboard/ledger';
import tahoModule from '@web3-onboard/taho';
import coinbaseModule from '@web3-onboard/coinbase';
import trezorModule from '@web3-onboard/trezor';
import magicModule from '@web3-onboard/magic';
import mewWallet from '@web3-onboard/mew-wallet';
import safeModule from '@web3-onboard/gnosis'

import { Buffer } from 'buffer';
import { ethers } from 'ethers';

const INFURA_KEY = 'bdb5aa7e7bd0434386d3dac2a29bab52';

window.Buffer = Buffer;

const injected = injectedModule();
const ledger = ledgerModule({ projectId: '6e92850ca8e4173ab55875d9f4225d79' });
const taho = tahoModule();
const coinbase = coinbaseModule();
const magic = magicModule({
    apiKey: 'pk_live_2CB1536A1C18BE7A',
    userEmail: localStorage.getItem('magicUserEmail'),
});
const mewWalletModule = mewWallet();

const trezor = trezorModule({ email: 'hkhan.swa@gmail.com', appUrl: 'https://testnet.platodata.io' });
const safe = safeModule(); // Add this line

// const [{ wallet, connecting }, connect, disconnect] = useConnectWallet();

const appMetadata = {
    name: 'PlatoAI Wallet Connect',
    icon: '<SVG_ICON_STRING>',
    logo: '<SVG_LOGO_STRING>',
    description: 'PlatoAI using Onboard',
    recommendedInjectedWallets: [
        { name: 'Coinbase', url: 'https://wallet.coinbase.com/' },
        { name: 'MetaMask', url: 'https://metamask.io' },
        { name: 'Safe', url: 'https://safe.global/' }
    ]
}
const onboard = Onboard({
    theme: 'dark',
    wallets: [injected, ledger, taho, coinbase, trezor, magic, mewWalletModule, safe],
    chains: [{
            id: '0x1',
            token: 'ETH',
            label: 'Ethereum Mainnet',
            rpcUrl: `https://mainnet.infura.io/v3/${INFURA_KEY}`
        },
        {
            id: '0x5',
            token: 'ETH',
            label: 'Goerli',
            rpcUrl: `https://goerli.infura.io/v3/${INFURA_KEY}`
        },
        {
            id: 11155111,
            token: 'ETH',
            label: 'Sepolia',
            rpcUrl: 'https://rpc.sepolia.org/'
        },
        {
            id: '0x89',
            token: 'MATIC',
            label: 'Matic',
            rpcUrl: 'https://matic-mainnet.chainstacklabs.com'
        },
        {
            id: '0x2105',
            token: 'ETH',
            label: 'Base',
            rpcUrl: 'https://mainnet.base.org'
        },
        {
            id: 42161,
            token: 'ARB-ETH',
            label: 'Arbitrum One',
            rpcUrl: 'https://rpc.ankr.com/arbitrum'
        },
        {
            id: '0xa4ba',
            token: 'ARB',
            label: 'Arbitrum Nova',
            rpcUrl: 'https://nova.arbitrum.io/rpc'
        },
        {
            id: 10,
            token: 'OETH',
            label: 'Optimism',
            rpcUrl: 'https://mainnet.optimism.io'
        }
    ],
    notify: {
        desktop: {
            enabled: true,
            transactionHandler: transaction => {
                console.log({ transaction })
                if (transaction.eventCode === 'txPool') {
                    return {
                        type: 'success',
                        message: 'Your transaction from #1 DApp is in the mempool',
                    }
                }
            },
            position: 'bottomLeft'
        },
        mobile: {
            enabled: true,
            transactionHandler: transaction => {
                console.log({ transaction })
                if (transaction.eventCode === 'txPool') {
                    return {
                        type: 'success',
                        message: 'Your transaction from #1 DApp is in the mempool',
                    }
                }
            },
            position: 'topRight'
        }
    },
    accountCenter: {
        desktop: {
            position: 'bottomRight',
            enabled: true,
            minimal: true
        },
        mobile: {
            position: 'topRight',
            enabled: true,
            minimal: true
        }
    },
    i18n: {
        en: {
            connect: {
                selectingWallet: {
                    header: 'custom text header'
                }
            },
            notify: {
                transaction: {
                    txStuck: 'custom text for this notification event'
                },
                watched: {
                    // Any words in brackets can be re-ordered or removed to fit your dapps desired verbiage
                    "txPool": "Your account is {verb} {formattedValue} {asset} {preposition} {counterpartyShortened}"
                }
            }
        },
        es: {
            transaction: {
                txRequest: 'Su transacción está esperando que confirme'
            }
        }
    }
});


const customTheme = {
    "--w3o-background-color": "#13141d",

    "--w3o-foreground-color": "#1f2228",
    "--w3o-text-color": "#ffffff",
    "--w3o-border-color": "unset",
    "--w3o-action-color": "unset",
    "--w3o-border-radius": "unset",
    "--w3o-font-family": "unset",
}
onboard.state.actions.updateTheme(customTheme)
let wallets = null;
async function connectWalletAndSendTransaction() {
    try {
        wallets = await onboard.connectWallet();
        console.log(wallets);
        if (wallets.length > 0 && wallets[0].provider) {


            // Check if the user property is defined before accessing its properties
            if (wallets[0].instance && wallets[0].instance.user) {
                try {
                    const { email, publicAddress } = await wallets[0].instance.user.getMetadata();
                    localStorage.setItem('magicUserEmail', email)

                    // Continue with the rest of your code...
                } catch (userError) {
                    console.error("Error retrieving user metadata:", userError);
                }
            } else {
                console.error("User property is not defined.");
            }
        } else {
            console.error("No wallet selected or provider not available.");
        }
    } catch (error) {
        console.error("Error connecting wallet:", error);
    }
}

let accounts = [];
// const paywithmetamask = document.getElementById("pay_with_metamask");
// paywithmetamask.addEventListener("click", async() => {

//     try {

//         //  const wallets = onboard.injectedModule.providers
//         if (wallets.length > 0 && wallets[0].provider) {
//             const provider = new ethers.providers.Web3Provider(wallets[0].provider);
//             const signer = provider.getSigner()

//             // send a transaction with the ethers provider
//             const valueInEther = '0.00001';
//             const valueInWei = ethers.utils.parseUnits(valueInEther, 'ether');
//             const valueHex = valueInWei.toHexString();
//             const txn = await signer.sendTransaction({
//                 to: '0x3fa62efb0a2aea36d6ef6524392db57ba359e270',
//                 value: valueHex
//             })

//         } else {
//             console.error("No wallet selected or provider not available.");
//         }
//     } catch (error) {
//         console.error("Error connecting wallet:", error);
//     }
// })
// paywithmetamask.addEventListener("abc", async function() {

//     try {
//         const accounts = await ethereum.request({ method: 'eth_requestAccounts' });

//         const valueInEther = '0.00001';
//         const valueInWei = ethers.utils.parseUnits(valueInEther, 'ether');
//         const valueHex = valueInWei.toHexString();
//         const transactionParameters = {
//             from: accounts[0],
//             to: '0x3fa62efb0a2aea36d6ef6524392db57ba359e270',
//             value: valueHex,
//             gasLimit: '0x5028',
//             maxPriorityFeePerGas: '0x3b9aca00',
//             maxFeePerGas: '0x2540be400',
//         };

//         const txHash = await ethereum.request({
//             method: 'eth_sendTransaction',
//             params: [transactionParameters],
//         });

//         console.log('Transaction Hash:', txHash);
//     } catch (error) {
//         console.error('Error sending transaction:', error);
//     }
// });


// const connectButton = document.getElementById('connectButton'); // Replace 'connectButton' with your actual button ID
// connectButton.addEventListener('click', connectWalletAndSendTransaction);