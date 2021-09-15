import 'package:flutter/material.dart';

class HomePageContent extends StatefulWidget {
  const HomePageContent({Key key}) : super(key: key);

  @override
  _HomePageContentState createState() => _HomePageContentState();
}

class _HomePageContentState extends State<HomePageContent> {
  @override
  Widget build(BuildContext context) {
    double height = MediaQuery.of(context).size.height;
    double width = MediaQuery.of(context).size.width;
    return Scaffold(
      body: Container(
        height: height,
        width: width,
        child: SafeArea(
          child: Column(
            children: <Widget>[
              _top(height, width),
            ],
          ),
        ),
      ),
    );
  }

  Widget _top(double height, double width) => Container(
        height: height * 0.08,
        child: Padding(
          padding: EdgeInsets.only(
            left: width * 0.03,
            right: width * 0.03,
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              IconButton(
                onPressed: () {},
                icon: Icon(
                  Icons.arrow_back,
                  color: Colors.transparent,
                ),
              ),
              Text(
                "Нүүр",
                style: TextStyle(
                  color: Colors.black,
                  fontSize: height * 0.02,
                  fontWeight: FontWeight.w500,
                ),
              ),
              IconButton(
                icon: Icon(
                  Icons.done_outline,
                  color: Colors.transparent,
                ),
                onPressed: () {},
              ),
            ],
          ),
        ),
      );
}
