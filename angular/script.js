var app = angular.module('app', ["ngRoute"]);

var app1 = angular.module('app1', []);

var app3 = angular.module('app3', ["app", "app1"]);

app.component("navbar", {
    bindings: {
        theme: "@",
        toggleTheme: "&"
    },
    templateUrl: 'navbar.html',
    controller: function($scope) {
        this.send = function(data) {
            this.title = 5
            this.showAlert({message:'This data will be send to the parent component' + data})
        }
    }
})

app.config(function ($routeProvider) {
    $routeProvider
        .when("/", {
          templateUrl: "home.html",
          title: "Home",
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

app.service("MathService", function() {
    this.add = function(a, b) { return a + b };

    this.subtract = function(a, b) { return a - b };

    this.multiply = function(a, b) { return a * b };

    this.divide = function(a, b) { return a / b };
})

app.controller("IndexController", function ($scope, $location) {
    $scope.theme = "dark"

    $scope.toggleTheme = function() {
        $scope.theme = $scope.theme == "dark" ? "light" : "dark"
    }

    $scope.isActive = function(destination) {
      return destination == $location.path()
    }

    $scope.title = 10

    $scope.$watch("title", function(oldValue, newValue){
      console.log(oldValue, newValue);
    })

    $scope.show = function(arg){
      console.log(arg);
    }

    $scope.message = "This is navbar of the application"
})

app.controller("HomeController", function ($scope, $http) {
    $scope.students = []
    $scope.isLoading = false

    $scope.deleteStudent = function(){
        if(!confirm("Are you sure you want to delete " + this.student.name)) return

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

    $scope.fetchStudents = function() {
        $http({
          url: "/ajax.php?action=get_students",
          method: "get"
        })
        .then(response => {
            $scope.students = response.data
            console.log(response.data);
        })
    }

    $scope.fetchStudents()
})

app.directive('fileModel', ['$parse', function ($parse) {
    return {
       restrict: 'A',
       link: function(scope, element, attrs) {
        console.log(attrs);
          var model = $parse(attrs.fileModel);
          var modelSetter = model.assign;

          scope.reset = function() {
                element[0].value = ""
                // console.log(document.querySelector("input[type=file]"));
                // console.log(element[0]);
                scope.student.image = ""
                document.querySelector("input[type=file]").value = ""
                console.log("call");
          }
          
        element.bind('change', function() {
             scope.$apply(function() {
                modelSetter(scope, element[0].files[0]);
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
        active: false,
        courses: [
            { name: "PHP", value: true },
            { name: "Java", value: true },
            { name: "Javascript", value: false },
        ]
    }
    $scope.isLoading = false 

    $scope.$watch('student.courses[0].value', function(newValue, oldValue) {
        console.log(newValue, oldValue, " Check box changed");
    })

    $scope.change = function(oldValue, newValue) {
        console.log("Old value", oldValue);
        console.log("New value", newValue);
    }

    $scope.createStudent = async function(){
        const payload = new FormData()
        Object.keys($scope.student).forEach(key => {
            if(key == "courses") {
                $scope.student[key].forEach((course, index) => {
                    console.log(`${key}[${index}][name]`);
                    payload.append(`${key}[${index}][name]`, course.name)
                    payload.append(`${key}[${index}][value]`, course.value)
                })
            } else {
                payload.append(key, $scope.student[key])
            }
        })

        const response = await fetch("/ajax.php?action=create_student", {
            method: "post",
            body: payload
        })

        console.log(await response.text());

        $scope.student = {
            name: "",
            email: "",
            phone: ""
        }

        $location.path('/')
        $scope.$apply()
    }
})



app.controller("EditStudentController", function ($scope, $location) {
    $scope.student = {
        id: $location.search().id,
        name: "",
        email: "",
        phone: ""
    }
    $scope.isLoading = true 
    
    fetch("/ajax.php?action=get_student&&id=" + $location.search().id)
    .then(async response => {
        $scope.student = await response.json()
        $scope.$apply();
    })

    $scope.updateStudent = async function(){
        const payload = new FormData()
        Object.keys($scope.student).forEach(key => {
            payload.append(key, $scope.student[key])
        })
    
        await fetch("/ajax.php?action=update_student", {
            method: "post",
            body: payload
        })

        $location.path('/')
        $scope.$apply()
    }
})

app.controller('MainController', function ($scope) {
    $scope.$on("FirstControllerEvent", function(event, data) {
      $scope.$broadcast("MainControllerBoardCast", data)
    })
})

app.controller('FirstController', function ($scope) {
    $scope.sendEvent = function() {
      $scope.$emit("FirstControllerEvent", "This is data")
    }
})

app.controller('SecondController', function ($scope) {
    $scope.$on("MainControllerBoardCast", function(event, data) {
        $scope.data = data
    })
})


