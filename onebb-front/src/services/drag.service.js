class Drag {
    init(el) {
        let initX, initY, firstX, firstY;
        document.querySelectorAll(el).forEach((object) => {  
            console.log(object);
            object.addEventListener('mousedown', function(e) {
                console.log(e);
                e.preventDefault();
                initX = this.offsetLeft;
                initY = this.offsetTop;
                firstX = e.pageX;
                firstY = e.pageY;

                this.addEventListener('mousemove', dragIt, false);

                window.addEventListener('mouseup', function() {
                    object.removeEventListener('mousemove', dragIt, false);
                }, false);

            }, false);

            object.addEventListener('touchstart', function(e) {
                console.log(e);
                e.preventDefault();
                initX = this.offsetLeft;
                initY = this.offsetTop;
                var touch = e.touches;
                firstX = touch[0].pageX;
                firstY = touch[0].pageY;

                this.addEventListener('touchmove', swipeIt, false);

                window.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    object.removeEventListener('touchmove', swipeIt, false);
                }, false);

            }, false);
        });
        function dragIt(e) {
            console.log(e);
            this.style.left = initX+e.pageX-firstX + 'px';
            this.style.top = initY+e.pageY-firstY + 'px';
        }

        function swipeIt(e) {
            var contact = e.touches;
            this.style.left = initX+contact[0].pageX-firstX + 'px';
            this.style.top = initY+contact[0].pageY-firstY + 'px';
        }
    }
}

export default new Drag();