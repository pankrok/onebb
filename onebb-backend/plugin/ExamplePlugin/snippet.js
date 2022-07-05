<script>
document.onreadystatechange = function () {
    if (document.readyState == 'interactive') {
            
        let el = document.querySelector('#examplePluginContent');
        if (el !== null) {
            window.$obbPlugins
            .dispatch('ExamplePlugin', 'myEvent')
            .then(response => {
                
                console.log(response.response);
                //document.querySelector('#examplePluginContent').append(response.response)
            });
        }
            
    }
}
</script>