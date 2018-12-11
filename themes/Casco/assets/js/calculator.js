function jsonPost(json) {
    return Object.keys(json).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(json[k])
    }).join('&')
}

Data = JSON.parse(atob(Data));

new Vue({
    el: '#app',
    components: {
        'vueSlider': window['vue-slider-component'],
    },
    data: {
        Data,
        car: Data.car.toUpperCase(),
        newCar: 0,
        carYear: Data.carYear,
        yearsCar: [],
        ages: Data.ages,
        ageRaw: Data.currentAge,
        experiences: Data.experiences,
        experienceRaw: Data.currentExperience,
        years: Data.years,
        price: Data.price,
        CascoOptimalRender: 0,
        CascoMinimalRender: 0,
        CascoFullRender: 0,
        q2: Data.regionalFactor,
        cascoPlan: 'Для автокредита',
        formSended: false,
        timers: {minimal: null, optimal: null, full: null}
    },
    computed: {
        age() {
            let age = Math.abs(parseInt(this.ageRaw));
            if (isNaN(age)) {
                age = 18;
            }
            if (age < 18) age = 18;
            if (age > 80) {
                age = 80;
                this.ageRaw = age;
            }

            return age;
        },
        experience(val) {
            let experience = Math.abs(parseInt(this.experienceRaw));
            if (isNaN(experience)) experience = 0;
            if (experience < 0) {
                experience = 0;
                this.experienceRaw = experience;
            }
            if (experience > 80) {
                experience = 80;
                this.experienceRaw = experience;
            }
            if (experience + 18 > this.age) {
                experience = this.age - 18;
            }
            return experience;
        },
        q1() {
            let years = 0;
            if (this.carYear === 'новый') {
                this.newCar = 1;
            } else {
                years = (new Date()).getFullYear() - this.carYear;
                this.newCar = 0;
            }
            return this.Data.cars[this.car][years];
        },
        q3() {
            let row = Data.experiences.findIndex((el, i) => {

                    if ((i === Data.experiences.length - 1) && this.experience >= el) return true;

                    if (this.experience >= el[0] && this.experience <= el[1]) return true;

                }),
                column = this.ages.findIndex((el, i) => {

                    if ((i === Data.ages.length - 1) && this.age >= el) return true;

                    if (this.age >= el[0] && this.age <= el[1]) return true;

                });
            return Data.q3[row][column];
        },
        q4() {
            let index = 0;
            index = (this.carYear == 'новый') ? 0 : 1;
            return Data.q4[this.newCar];
        },
        q5() {
            return Data.q5.find((el, i) => {
                if (i === Data.q5.length - 1) {
                    if (this.price >= el[0]) return true;
                }
                if (this.price <= el[0]) return true;
            })[1];
        },
        CascoMinimal() {
            return Math.floor(parseInt(this.price * this.q1 * this.q2 * this.q3 * this.q4 * Data.q6 * Data.q7 * Data.q8) / 100) * 100;
        },
        CascoOptimal() {
            return Math.floor(parseInt(this.price * this.q1 * this.q2 * this.q3 * this.q4 * this.q5 * Data.q7 * Data.q8) / 100) * 100;
        },
        CascoFull() {
            return Math.floor(parseInt(this.price * this.q1 * this.q2 * this.q3 * this.q4 * Data.q7 * Data.q8) / 100) * 100;
        }
    },
    watch: {
        /*experience(val) {
            if (this.age <= (18 + val)) this.ageRaw = 18 + val;
        },
        age(val, prev) {
            if (val < prev && (this.experience + 18) > val) {
                this.experienceRaw -= 1;
            }
        },*/
        CascoMinimal(val) {
            this.animate(val, 'minimal', 'CascoMinimalRender');
        },
        CascoOptimal(val) {
            this.animate(val, 'optimal', 'CascoOptimalRender');
        },
        CascoFull(val) {
            this.animate(val, 'full', 'CascoFullRender');
        }
    },
    methods: {
        range(min, max) {
            let array = [],
                j = 0;
            for (let i = min; i <= max; i++) {
                array[j] = i;
                j++;
            }
            return array;
        },
        animate(val, timer, price) {
            let start = this[price], end = val,
                range = end - start,
                current = start,
                increment = end > start ? 100 : -100,
                stepTime = 1;
            clearInterval(this.timers[timer]);
            this.timers[timer] = setInterval(() => {
                current += increment;
                this[price] = current;
                if (increment === 100 && current >= end || increment === -100 && current <= end) {
                    clearInterval(this.timers[timer]);
                }
            }, stepTime);
        },
        sendForm(e = null, subject = 0) {
            if (e) {
                e.preventDefault();
            }
            const form = document.forms.namedItem("cascoForm"),
                name = form.name.value,
                email = form.email.value,
                phone = form.phone.value;
            if (phone) {
                let data = {
                    action: 'casco',
                    subject,
                    name,
                    email,
                    phone,
                    casco: {
                        car: this.Data.name,
                        price: this.$options.filters.price(this.price),
                        age: this.$options.filters.yearsRu(this.age),
                        experience: this.$options.filters.yearsRu(this.experience),
                        car_year: this.carYear,
                        plan: this.cascoPlan
                    }
                };

                data.casco = jsonPost(data.casco);

                data = jsonPost(data);

                axios.post(adminAjax, data)
                    .then(() => {
                        this.formSended = true;
                        $('#order').modal('hide');
                        if (subject !== 1) {
                            setTimeout(() => {
                                $('#thanks').modal('show');
                                setTimeout(() => $('#thanks').modal('hide'), 5000);
                            }, 300);
                        }
                    });
            }
        }
    },
    filters: {
        price(val) {
            let c = 0,
                d = " ",
                t = " ",
                s = '',
                i = String(parseInt(val = Math.abs(Number(val) || 0).toFixed(c))),
                j = i.length;

            j = j > 3 ? j % 3 : 0;

            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(val - i).toFixed(c).slice(2) : "");
        },
        priceFormat(val) {
            return (val / 1000).toFixed(3).toString().replace(".", " ")
        },
        yearsRu(val) {
            let string = val + '',
                lastChar = parseInt(string[string.length - 1]);
            if ([0, 5, 6, 7, 8, 9].indexOf(lastChar) !== -1) {
                string += ' лет';
            }
            if ([1].indexOf(lastChar) !== -1) {
                string += ' год';
            }
            if ([2, 3, 4].indexOf(lastChar) !== -1) {
                string += ' года';
            }
            return string;
        },
        carStatus(val) {
            return val ? 'новый' : 'б/у';
        }
    },
    created() {
        this.yearsCar = ['новый'];
        for (let i = this.Data.years.max; i >= this.Data.years.min; i--) {
            this.yearsCar.push(i);
        }
    },
    mounted() {
        this.CascoMinimalRender = this.CascoMinimal;
        this.CascoOptimalRender = this.CascoOptimal;
        this.CascoFullRender = this.CascoFull;
        $('.preloader-wrapper').fadeOut();

        window.onbeforeunload = () => {
            if (!this.formSended) this.sendForm(null, 1);
        };

        $('.casco-item').click(function () {
            let id = $(this).attr('data-id');
            $('.compare-item').removeClass('active');
            $(`.compare-item:nth-child(${id})`).addClass('active');
            setTimeout(() => {
                let top = $(`.compare-item:nth-child(${id})`)[0].getBoundingClientRect().top - 50;
                $('#cascocompare').animate({scrollTop: top}, 400);
            }, 300);
        });
        $('.phone').inputmask("mask", {"mask": "+7 (999) 999-99-99", "clearIncomplete": true});
    }
}); 
