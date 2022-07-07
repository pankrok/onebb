<script>
    document.addEventListener('readystatechange', function() {
        if (document.readyState === 'complete') {
            window.$obbPlugins.subscribe('Home', function(){
            window.$obbPlugins
                .dispatch('ExamplePlugin', 'myEvent')
                .then(response => {
                    document.querySelector('#examplePluginContent').innerHTML = (response.data)
                });
            })   
        }
    }); 
</script>