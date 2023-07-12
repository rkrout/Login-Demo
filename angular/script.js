var app = angular.module('app', ["ngRoute"]);

var app1 = angular.module('app1', []);

var app3 = angular.module('app3', ["app", "app1"]);

app.component("navbar", {
    bindings: {
        theme: "@",
        toggleTheme: "&"
    },
    templateUrl: 'navbar.html',
    controller: function ($scope) {
        this.send = function (data) {
            this.title = 5
            this.showAlert({ message: 'This data will be send to the parent component' + data })
        }
    }
})

app.config(function ($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl: "home.html",
            controller: "HomeController"
        })
        .when("/create", {
            templateUrl: "create-student.html",
            controller: "CreateStudentController"
        })
        .when("/edit", {
            templateUrl: "edit-student.html",
            controller: "EditStudentController"
        })
        .when("/contact", {
            templateUrl: "contact.html",
            controller: "ContactController"
        })
        .otherwise({ redirectTo: "/" })
})

app.service("MathService", function () {
    this.add = function (a, b) { return a + b };

    this.subtract = function (a, b) { return a - b };

    this.multiply = function (a, b) { return a * b };

    this.divide = function (a, b) { return a / b };
})

app.controller("IndexController", function ($scope, $location) {
    $scope.theme = "Dark"

    $scope.toggleTheme = function () {
        $scope.theme = $scope.theme == "Dark" ? "Light" : "Dark"
    }
})

app.controller("HomeController", function ($scope, $http) {
    $scope.students = []

    $scope.deleteStudent = function () {
        if (!confirm("Are you sure you want to delete " + this.student.name)) return

        const payload = new FormData()
        payload.append("id", this.student.id)

        fetch("/ajax.php?action=delete_student", {
            method: "post",
            body: payload
        })
        .then(response => {
            $scope.fetchStudents()
        })
    }

    $scope.fetchStudents = function () {
        fetch("/ajax.php?action=get_students")
        .then(async response => {
            $scope.students = await response.json()
            $scope.$apply()
        })
    }

    $scope.fetchStudents()
})

app.directive("checkModel", function () {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element[0].checked = scope.student.course_ids.find(course => course == attrs.value)

            element.bind("change", function (event) {
                if (event.target.checked) {
                    scope.student.course_ids.push(Number(attrs.value))
                } else {
                    scope.student.course_ids = scope.student.course_ids.filter(id => id != Number(attrs.value))
                }
            })
        }
    }
})

app.directive("fileModel", ["$parse", function ($parse) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            console.log(attrs);
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            scope.resetImage = function () {
                element[0].value = ""
                scope.student.image = ""
                scope.student.image_url = ""
            }

            element.bind("change", function () {
                scope.$apply(function () {
                    modelSetter(scope, element[0].files[0]);
                    scope.student.image_url = URL.createObjectURL(element[0].files[0])
                });
            });
        }
    };
}]);


app.controller("CreateStudentController", function ($scope, $location) {
    $scope.student = {
        name: "",
        email: "",
        phone: "",
        image: "",
        image_url: "",
        is_active: false,
        course_ids: [],
        gender: "",
        address: ""
    }

    $scope.courses = []

    $scope.createStudent = async function () {
        const payload = new FormData()
        Object.keys($scope.student).forEach(key => {
            if (key == "course_ids") {
                $scope.student[key].forEach((course, index) => {
                    payload.append(`${key}[${index}]`, course)
                })
            } else {
                payload.append(key, $scope.student[key])
            }
        })

        await fetch("/ajax.php?action=create_student", {
            method: "post",
            body: payload
        })

        $location.path("/")
        $scope.$apply()
    }

    fetch("/ajax.php?action=get_courses")
        .then(async response => {
            $scope.courses = await response.json()
            $scope.$apply()
        })
})


app.controller("EditStudentController", function ($scope, $location) {
    $scope.student = {
        id: $location.search().id
    }

    $scope.courses = []

    $scope.updateStudent = async function () {
        const payload = new FormData()
        Object.keys($scope.student).forEach(key => {
            if (key == "course_ids") {
                $scope.student[key].forEach((course, index) => {
                    payload.append(`${key}[${index}]`, course)
                })
            } else {
                payload.append(key, $scope.student[key])
            }
        })

        await fetch("/ajax.php?action=update_student", {
            method: "post",
            body: payload
        })

        $location.path('/')
        $scope.$apply()
    }

    fetch("/ajax.php?action=get_student&&id=" + $location.search().id)
        .then(async response => {
            const data = await response.json()

            $scope.student = {
                ...data,
                image: ""
            }

            $scope.$apply()
        })

    fetch("/ajax.php?action=get_courses")
        .then(async response => {
            $scope.courses = await response.json()
            $scope.$apply();
        })
})

app.controller('MainController', function ($scope) {
    $scope.$on("FirstControllerEvent", function (event, data) {
        $scope.$broadcast("MainControllerBoardCast", data)
    })
})

app.controller('FirstController', function ($scope) {
    $scope.sendEvent = function () {
        $scope.$emit("FirstControllerEvent", "This is data")
    }
})

app.controller('SecondController', function ($scope) {
    $scope.$on("MainControllerBoardCast", function (event, data) {
        $scope.data = data
    })
})


