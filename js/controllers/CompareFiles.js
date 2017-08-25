
app.controller('CompareFiles', function ($scope, CompareFactory) {
    
    $scope.spath = '/Library/Server/Web/Data/Sites/Default/cap/text1.txt';
    $scope.fpath = '/Library/Server/Web/Data/Sites/Default/cap/text2.txt';


    $scope.findDuplicatePhrases = function () {
        CompareFactory.get([$scope.spath, $scope.fpath])
                .then(function(response){
                    console.log(response);
        });
    };
});

