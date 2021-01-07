export default {
    closeModal(id){
        $(`#${id}`).modal('hide')
    },
    showModal(id){
        $(`#${id}`).modal('show')
    },
    getFileName(file){
        var tokens= file.split('\\');//split path
        return tokens[tokens.length-1];//take file name
    },
}
