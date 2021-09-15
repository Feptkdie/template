import 'package:flutter/material.dart';
// import 'package:fluttertoast/fluttertoast.dart';

import '../constants.dart';

void showSnackBar(String value, GlobalKey<ScaffoldState> scaffold) {
  scaffold.currentState.showSnackBar(new SnackBar(
      duration: const Duration(milliseconds: 1000), content: new Text(value)));
}

loading(bool status, BuildContext context) {
  if (status == true) {
    return showDialog(
      barrierDismissible: false,
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          content: Container(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              mainAxisSize: MainAxisSize.min,
              children: [
                Center(
                  child: CircularProgressIndicator(
                    valueColor: AlwaysStoppedAnimation<Color>(kPrimaryColor),
                  ),
                ),
                SizedBox(
                  height: 12.0,
                ),
                Text("Loading"),
              ],
            ),
          ),
        );
      },
    );
  } else {
    Navigator.pop(context);
  }
}

// void showMessage(
//   String message,
//   Color backgroundColor,
//   Color textColor,
// ) {
//   Fluttertoast.showToast(
//     msg: message,
//     toastLength: Toast.LENGTH_SHORT,
//     gravity: ToastGravity.BOTTOM,
//     timeInSecForIosWeb: 1,
//     backgroundColor: backgroundColor,
//     textColor: textColor,
//     fontSize: 16.0,
//   );
// }
